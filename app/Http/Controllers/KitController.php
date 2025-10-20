<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kit;

class KitController extends Controller
{
    /**
     * Retorna los componentes de un kit en formato JSON.
     */
    public function componentes($id)
    {
        // Cargar componentes del kit. La tabla kit_componentes contiene el nombre del componente
        $kit = Kit::with('componentes')->findOrFail($id);

        $data = $kit->componentes->map(function($kc) {
            return [
                'id_componente' => $kc->id_componente,
                'nombre' => $kc->nombre_componente ?? $kc->descripcion ?? 'Componente',
                'stock' => property_exists($kc, 'stock') ? $kc->stock : ($kc->cantidad_en_kit ?? 0),
                'cantidad_por_kit' => $kc->cantidad_en_kit ?? 1,
            ];
        })->values();

        return response()->json($data);
    }
}
