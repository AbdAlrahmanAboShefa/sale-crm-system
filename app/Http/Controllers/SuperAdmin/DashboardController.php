<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalTenants' => Tenant::count(),
            'activeTenants' => Tenant::where('is_active', true)->count(),
            'trialTenants' => Tenant::whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now())->count(),
            'totalUsers' => User::count(),
            'totalDeals' => Deal::count(),
            'totalContacts' => Contact::count(),
            'totalValue' => Deal::whereNotIn('stage', ['Won', 'Lost'])->sum('value'),
            'wonDeals' => Deal::where('stage', 'Won')->count(),
        ];

        $pipelineData = Deal::selectRaw('stage, COUNT(*) as count, SUM(value) as total_value')
            ->groupBy('stage')
            ->get()
            ->keyBy('stage');

        $stageStats = [];
        foreach (Deal::STAGES as $stage) {
            $stageStats[$stage] = [
                'count' => $pipelineData[$stage]->count ?? 0,
                'value' => $pipelineData[$stage]->total_value ?? 0,
            ];
        }

        $recentActivities = Activity::with(['contact', 'deal', 'user', 'deal.tenant'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentDeals = Deal::with(['contact', 'user', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $tenantsWithStats = Tenant::withCount(['users', 'contacts', 'deals'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('super_admin.dashboard', compact(
            'stats',
            'stageStats',
            'recentActivities',
            'recentDeals',
            'tenantsWithStats'
        ));
    }
}
