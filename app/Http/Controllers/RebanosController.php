<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RebanosServiceInterface;
use Illuminate\Http\Request;

class RebanosController extends Controller
{
    protected RebanosServiceInterface $rebanosService;

    public function __construct(RebanosServiceInterface $rebanosService)
    {
        $this->rebanosService = $rebanosService;
    }

    /**
     * Display list of rebaños
     */
    public function index()
    {
        $response = $this->rebanosService->getRebanos();

        if (isset($response['success']) && $response['success']) {
            $rebanos = $response['data']['data'] ?? [];
            $pagination = [
                'current_page' => $response['data']['current_page'] ?? 1,
                'last_page' => $response['data']['last_page'] ?? 1,
                'total' => $response['data']['total'] ?? 0,
            ];

            return view('rebanos.index', compact('rebanos', 'pagination'));
        }

        return view('rebanos.index', [
            'rebanos' => [],
            'pagination' => [],
            'error' => $response['message'] ?? 'Error al obtener los rebaños'
        ]);
    }
}
