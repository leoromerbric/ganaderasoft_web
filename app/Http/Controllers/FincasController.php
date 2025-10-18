<?php

namespace App\Http\Controllers;

use App\Services\Contracts\FincasServiceInterface;
use Illuminate\Http\Request;

class FincasController extends Controller
{
    protected FincasServiceInterface $fincasService;

    public function __construct(FincasServiceInterface $fincasService)
    {
        $this->fincasService = $fincasService;
    }

    /**
     * Display list of fincas
     */
    public function index()
    {
        $response = $this->fincasService->getFincas();

        if (isset($response['success']) && $response['success']) {
            $fincas = $response['data']['data'] ?? [];
            $pagination = [
                'current_page' => $response['data']['current_page'] ?? 1,
                'last_page' => $response['data']['last_page'] ?? 1,
                'total' => $response['data']['total'] ?? 0,
            ];

            return view('fincas.index', compact('fincas', 'pagination'));
        }

        return view('fincas.index', [
            'fincas' => [],
            'pagination' => [],
            'error' => $response['message'] ?? 'Error al obtener las fincas'
        ]);
    }
}
