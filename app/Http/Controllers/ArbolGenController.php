<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ArbolGenServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArbolGenController extends Controller
{
    /**
     * Inyecta el servicio para la gestión del árbol genealógico.
     *
     * @param ArbolGenServiceInterface $service
     */
    public function __construct(
        protected ArbolGenServiceInterface $service
    ) {}

    /**
     * Muestra la vista principal del árbol genealógico de un animal.
     *
     * @param int $id Identificador del animal.
     * @return View|RedirectResponse
     */
    public function show(int $id): View|RedirectResponse
    {
        $response = $this->service->getArbol($id);

        if (!($response['success'] ?? false)) {
            return redirect()->route('animales.show', $id)
                ->with('error', 'No se pudo cargar el árbol genealógico.');
        }

        $arbol = $response['data'] ?? [];

        return view('animales.arbol', compact('id', 'arbol'));
    }

    /**
     * Registra o actualiza la relación de un progenitor (Padre o Madre) para un animal.
     *
     * @param Request $request Petición con el tipo ('Padre'|'Madre') y padre_id.
     * @param int $id Identificador del animal hijo.
     * @return RedirectResponse
     */
    public function store(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'tipo'     => 'required|in:Padre,Madre',
            'padre_id' => 'required|integer',
        ]);

        $response = $this->service->setProgenitor($id, $request->only(['tipo', 'padre_id']));

        if (!($response['success'] ?? false)) {
            $msg = $this->apiMessage($response, 'No se pudo guardar la relación.');
            return redirect()->route('arbol-gen.show', $id)->with('error', $msg);
        }

        return redirect()->route('arbol-gen.show', $id)
            ->with('success', $response['message'] ?? 'Relación guardada correctamente.');
    }

    /**
     * Elimina la relación de un progenitor (Padre o Madre) de un animal.
     *
     * @param int $id Identificador del animal hijo.
     * @param string $tipo Tipo de relación a eliminar ('Padre' o 'Madre').
     * @return RedirectResponse
     */
    public function destroy(int $id, string $tipo): RedirectResponse
    {
        $response = $this->service->removeProgenitor($id, $tipo);

        if (!($response['success'] ?? false)) {
            return redirect()->route('arbol-gen.show', $id)
                ->with('error', $response['message'] ?? 'No se pudo eliminar la relación.');
        }

        return redirect()->route('arbol-gen.show', $id)
            ->with('success', $response['message'] ?? "Relación de {$tipo} eliminada.");
    }

    /**
     * Endpoint AJAX para consultar la lista de animales disponibles para asignar como progenitor.
     *
     * @param Request $request Petición con el parámetro de consulta 'tipo'.
     * @param int $id Identificador del animal hijo.
     * @return JsonResponse
     */
    public function disponibles(Request $request, int $id): JsonResponse
    {
        $tipo = (string) $request->query('tipo', '');
        $response = $this->service->getDisponibles($id, $tipo);

        return response()->json($response);
    }

    /**
     * Extrae un mensaje de error legible a partir de la respuesta de la API o usa un fallback.
     *
     * @param array $response Respuesta retornada por el servicio.
     * @param string $fallback Mensaje por defecto en caso de no encontrar detalle.
     * @return string Mensaje formateado para el usuario.
     */
    private function apiMessage(array $response, string $fallback): string
    {
        if (!empty($response['message']) && is_string($response['message'])) {
            return $response['message'];
        }

        if (!empty($response['errors']) && is_array($response['errors'])) {
            $first = collect($response['errors'])->flatten()->first();
            if (is_string($first) && $first !== '') {
                return $first;
            }
        }

        return $fallback;
    }
}
