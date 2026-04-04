<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $isAdminOrManager = true;

        $stats = [
            'totalDeals' => Deal::count(),
            'totalContacts' => Contact::count(),
            'totalValue' => Deal::whereNotIn('stage', ['Won', 'Lost'])->sum('value'),
            'wonDeals' => Deal::where('stage', 'Won')->count(),
            'activeDeals' => Deal::whereNotIn('stage', ['Won', 'Lost'])->count(),
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

        $recentActivities = Activity::with(['contact', 'deal', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $monthlyDeals = Deal::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(value) as value')
            ->whereYear('created_at', date('Y'))
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month');

        return view('admin.dashboard', compact(
            'stats',
            'stageStats',
            'recentActivities',
            'monthlyDeals',
            'isAdminOrManager'
        ));
    }
}
