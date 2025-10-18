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
        $fincaId = request()->query('id_finca');
        
        $farms = $this->dashboardService->getFarms();
        $kpis = $this->dashboardService->getKPIs($fincaId);
        $chartData = $this->dashboardService->getProductionChartData($fincaId);
        $alerts = $this->dashboardService->getRecentAlerts();
        $statistics = $this->dashboardService->getFarmStatistics($fincaId);

        return view('dashboard.index', compact('user', 'kpis', 'chartData', 'alerts', 'farms', 'fincaId', 'statistics'));
    }
}
