<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardController extends Controller
{
    protected DashboardServiceInterface $dashboardService;

    protected AuthServiceInterface $authService;

    public function __construct(
        DashboardServiceInterface $dashboardService,
        AuthServiceInterface $authService
    ) {
        $this->dashboardService = $dashboardService;
        $this->authService = $authService;
    }

    /**
     * Show the dashboard
     */
    public function index()
    {
        $user = $this->authService->user();
        $kpis = $this->dashboardService->getKPIs();
        $chartData = $this->dashboardService->getProductionChartData();
        $alerts = $this->dashboardService->getRecentAlerts();

        return view('dashboard.index', compact('user', 'kpis', 'chartData', 'alerts'));
    }
}
