<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ArbolGenServiceInterface;
use Illuminate\Http\Request;

class ArbolGenController extends Controller
{
    public function __construct(protected ArbolGenServiceInterface $service)
    {
    }

    /** Vista principal del árbol genealógico de un animal. */
    public function show(int $id)
    {
        $response = $this->service->getArbol($id);

        if (!($response['success'] ?? false)) {
            return redirect()->route('animales.show', $id)
                ->with('error', 'No se pudo cargar el árbol genealógico.');
        }

        $arbol = $response['data'];

        return view('animales.arbol', compact('id', 'arbol'));
    }

    /** Registra o actualiza un progenitor (Padre o Madre). */
    public function store(Request $request, int $id)
    {
        $request->validate([
            'tipo'     => 'required|in:Padre,Madre',
            'id_padre' => 'required|integer',
        ]);

        $response = $this->service->setProgenitor($id, $request->only(['tipo', 'id_padre']));

        if (!($response['success'] ?? false)) {
            $msg = $this->apiMessage($response, 'No se pudo guardar la relación.');
            return redirect()->route('arbol-gen.show', $id)->with('error', $msg);
        }

        return redirect()->route('arbol-gen.show', $id)
            ->with('success', $response['message'] ?? 'Relación guardada correctamente.');
    }

    /** Elimina la relación de Padre o Madre. */
    public function destroy(int $id, string $tipo)
    {
        $response = $this->service->removeProgenitor($id, $tipo);

        if (!($response['success'] ?? false)) {
            return redirect()->route('arbol-gen.show', $id)
                ->with('error', $response['message'] ?? 'No se pudo eliminar la relación.');
        }

        return redirect()->route('arbol-gen.show', $id)
            ->with('success', "Relación de {$tipo} eliminada.");
    }

    /** AJAX: lista de animales disponibles para progenitor. */
    public function disponibles(Request $request, int $id)
    {
        $tipo = $request->query('tipo', '');
        $response = $this->service->getDisponibles($id, $tipo);

        return response()->json($response);
    }

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
