<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PersonalServiceInterface;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    protected PersonalServiceInterface $personalService;

    public function __construct(PersonalServiceInterface $personalService)
    {
        $this->personalService = $personalService;
    }

    /**
     * Display list of personal for a finca
     */
    public function index(Request $request)
    {
        $idFinca = $request->query('id_finca');

        if (!$idFinca) {
            return view('personal.index', [
                'personal' => [],
                'pagination' => [],
                'error' => 'Debe seleccionar una finca'
            ]);
        }

        $response = $this->personalService->getPersonal($idFinca);

        if (isset($response['success']) && $response['success']) {
            $personal = $response['data'] ?? [];
            $pagination = $response['pagination'] ?? [
                'current_page' => 1,
                'last_page' => 1,
                'total' => count($personal),
            ];

            return view('personal.index', compact('personal', 'pagination', 'idFinca'));
        }

        return view('personal.index', [
            'personal' => [],
            'pagination' => [],
            'idFinca' => $idFinca,
            'error' => $response['message'] ?? 'Error al obtener el personal'
        ]);
    }
}
