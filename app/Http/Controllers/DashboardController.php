<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        $userId = $user->id;

        $kpis = [
            'pipelineValue' => $this->dashboardService->getTotalPipelineValue($isAdminOrManager, $userId),
            'wonThisMonth' => $this->dashboardService->getWonThisMonth($isAdminOrManager, $userId),
            'conversionRate' => $this->dashboardService->getConversionRate($isAdminOrManager, $userId),
            'overdueActivities' => $this->dashboardService->getOverdueActivities($isAdminOrManager, $userId),
        ];

        $monthlyRevenue = $this->dashboardService->getMonthlyRevenue($isAdminOrManager, $userId);
        $pipelineFunnel = $this->dashboardService->getPipelineFunnel($isAdminOrManager, $userId);
        $leaderboard = $isAdminOrManager ? $this->dashboardService->getLeaderboard() : [];
        $recentActivities = $this->dashboardService->getRecentActivities($isAdminOrManager, $userId);
        $topDeals = $this->dashboardService->getTopDeals($isAdminOrManager, $userId);
        $routePrefix = $user->hasRole('Admin') ? 'admin' : ($user->hasRole('Manager') ? 'manager' : 'agent');

        return view('dashboard', compact('kpis', 'monthlyRevenue', 'pipelineFunnel', 'leaderboard', 'isAdminOrManager', 'recentActivities', 'topDeals', 'routePrefix'));
    }
}
