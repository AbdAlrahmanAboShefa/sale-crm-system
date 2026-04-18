<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentIdempotencyKey;
use App\Models\PaymentTransaction;
use App\Models\Tenant;
use App\Services\Payment\PaymentManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentDashboardController extends Controller
{
    public function __construct(
        protected PaymentManager $paymentManager
    ) {}

    /**
     * Display the payment dashboard.
     */
    public function index(Request $request): View
    {
        // Date range filter
        $days = $request->integer('days', 30);
        $startDate = now()->subDays($days);

        // Core payment stats
        $totalRevenue = PaymentTransaction::successful()->where('created_at', '>=', $startDate)->sum('amount');
        $totalTransactions = PaymentTransaction::where('created_at', '>=', $startDate)->count();
        $successfulTransactions = PaymentTransaction::successful()->where('created_at', '>=', $startDate)->count();
        $failedTransactions = PaymentTransaction::failed()->where('created_at', '>=', $startDate)->count();
        $pendingTransactions = PaymentTransaction::where('status', 'pending')->where('created_at', '>=', $startDate)->count();
        $refundedAmount = PaymentTransaction::where('status', 'refunded')->where('created_at', '>=', $startDate)->sum('amount');

        // Success rate
        $successRate = $totalTransactions > 0 ? round(($successfulTransactions / $totalTransactions) * 100, 1) : 0;

        // Revenue by gateway
        $revenueByGateway = PaymentTransaction::successful()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('gateway, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('gateway')
            ->get()
            ->mapWithKeys(fn($row) => [$row->gateway => ['count' => $row->count, 'total' => $row->total]]);

        // Revenue by plan
        $revenueByPlan = Tenant::whereIn('plan', ['basic', 'pro', 'enterprise'])
            ->withCount(['paymentTransactions' => function ($query) use ($startDate) {
                $query->where('status', 'completed')
                    ->where('created_at', '>=', $startDate);
            }])
            ->selectRaw("
                plan,
                COUNT(*) as subscriber_count,
                (SELECT SUM(amount) FROM payment_transactions 
                 WHERE payment_transactions.tenant_id = tenants.id 
                 AND payment_transactions.status = 'completed' 
                 AND payment_transactions.created_at >= ?) as revenue
            ", [$startDate])
            ->groupBy('plan')
            ->get()
            ->mapWithKeys(fn($row) => [$row->plan => [
                'count' => $row->subscriber_count,
                'revenue' => $row->revenue ?? 0,
            ]]);

        // Recent transactions
        $recentTransactions = PaymentTransaction::with(['tenant', 'paymentMethod'])
            ->latest()
            ->take(15)
            ->get();

        // Daily revenue chart data (last 30 days)
        $dailyRevenue = PaymentTransaction::successful()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($row) => [$row->date => ['total' => $row->total, 'count' => $row->count]]);

        // Fill missing dates with zeros
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[$date] = $dailyRevenue[$date] ?? ['total' => 0, 'count' => 0];
        }

        // Top paying tenants
        $topTenants = Tenant::withSum([
            'paymentTransactions as total_paid' => function ($query) use ($startDate) {
                $query->where('status', 'completed')
                    ->where('created_at', '>=', $startDate);
            }
        ], 'amount')
            ->having('total_paid', '>', 0)
            ->orderByDesc('total_paid')
            ->take(10)
            ->get();

        // Subscription stats
        $activeSubscriptions = Tenant::whereIn('plan', ['basic', 'pro', 'enterprise'])->count();
        $freeTenants = Tenant::where('plan', 'free')->count();
        $churnedThisMonth = PaymentTransaction::where('status', 'refunded')
            ->where('created_at', '>=', now()->startOfMonth())
            ->distinct('tenant_id')
            ->count('tenant_id');

        // Idempotency stats
        $idempotencyHits = PaymentIdempotencyKey::where('created_at', '>=', $startDate)->count();
        $cachedResponses = PaymentIdempotencyKey::where('created_at', '>=', $startDate)
            ->whereNotNull('response')
            ->count();

        // Plan distribution
        $planDistribution = Tenant::selectRaw("
            plan,
            COUNT(*) as count,
            (SELECT SUM(amount) FROM payment_transactions 
             WHERE payment_transactions.tenant_id = tenants.id 
             AND payment_transactions.status = 'completed') as total_revenue
        ")->groupBy('plan')->get();

        return view('super_admin.payments.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'successfulTransactions',
            'failedTransactions',
            'pendingTransactions',
            'refundedAmount',
            'successRate',
            'revenueByGateway',
            'revenueByPlan',
            'recentTransactions',
            'chartData',
            'topTenants',
            'activeSubscriptions',
            'freeTenants',
            'churnedThisMonth',
            'idempotencyHits',
            'cachedResponses',
            'planDistribution',
            'days',
        ));
    }
}
