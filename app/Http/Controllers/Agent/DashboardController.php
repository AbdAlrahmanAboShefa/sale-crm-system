<?php

namespace App\Http\Controllers\Agent;

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
        $isAdminOrManager = false;
        
        $stats = [
            'totalDeals' => Deal::where('user_id', $user->id)->count(),
            'totalContacts' => Contact::where('user_id', $user->id)->count(),
            'totalValue' => Deal::where('user_id', $user->id)->whereNotIn('stage', ['Won', 'Lost'])->sum('value'),
            'wonDeals' => Deal::where('user_id', $user->id)->where('stage', 'Won')->count(),
            'activeDeals' => Deal::where('user_id', $user->id)->whereNotIn('stage', ['Won', 'Lost'])->count(),
        ];

        $pipelineData = Deal::where('user_id', $user->id)
            ->selectRaw('stage, COUNT(*) as count, SUM(value) as total_value')
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
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $monthlyDeals = Deal::where('user_id', $user->id)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(value) as value')
            ->whereYear('created_at', date('Y'))
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month');

        return view('agent.dashboard', compact(
            'stats', 
            'stageStats', 
            'recentActivities',
            'monthlyDeals',
            'isAdminOrManager'
        ));
    }
}
