<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AdminServiceInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Muestra el Dashboard principal de Administración.
     */
    public function dashboard(Request $request)
    {
        $result = $this->adminService->getDashboardKpis();
        $kpis = $result['kpis'];
        $recentUsers = $result['recentUsers'];

        return view('admin.dashboard.index', compact('kpis', 'recentUsers'));
    }
}
