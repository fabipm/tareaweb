<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudKit;
use App\Models\AutorizacionKit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutorizacionKitController extends Controller
{
    public function aprobar($id)
    {
        $sol = SolicitudKit::with('kit','componentes')->findOrFail($id);

        // Ejecutar en transacción para mantener consistencia de stock
        try {
            DB::beginTransaction();

            $kit = $sol->kit;
            if (!$kit) {
                throw new \Exception('Kit asociado no encontrado');
            }

            // Validar stock disponible para el kit (se presta 1 unidad del kit)
            if (intval($kit->stock_disponible) <= 0) {
                throw new \Exception('No hay stock disponible del kit seleccionado');
            }

            // Validar y decrementar stock por componente (si hay componentes en la solicitud)
            $componentes = $sol->componentes; // relation SolicitudKitComponente
            foreach ($componentes as $skc) {
                $comp = \App\Models\KitComponente::find($skc->id_componente);
                if (!$comp) {
                    throw new \Exception('Componente ID ' . $skc->id_componente . ' no existe');
                }

                $solicitado = intval($skc->cantidad_solicitada);
                if ($solicitado <= 0) continue; // nada que hacer

                // Si la columna stock existe en kit_componentes, validarla
                if (property_exists($comp, 'stock') || isset($comp->stock)) {
                    if (intval($comp->stock) < $solicitado) {
                        throw new \Exception('Stock insuficiente para componente: ' . $comp->nombre_componente);
                    }
                    // decrementar stock del componente
                    $comp->stock = intval($comp->stock) - $solicitado;
                    $comp->save();
                }

                // Registrar cantidad entregada
                $skc->cantidad_entregada = $solicitado;
                $skc->save();
            }

            // Insertar autorización
            DB::table('autorizaciones_kit')->insert([
                'id_solicitud_kit' => $id,
                'autorizado_por' => Auth::id(),
                'fecha_autorizacion' => now(),
                'estado' => 'Aprobada'
            ]);

            // Decrementar stock del kit (unidad física del kit)
            if (intval($kit->stock_disponible) > 0) {
                $kit->stock_disponible = intval($kit->stock_disponible) - 1;
                $kit->save();
            }

            // Actualizar estado de la solicitud
            $sol->estado = 'Autorizada';
            $sol->save();

            DB::commit();
            return back()->with('success','Solicitud de kit aprobada');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo aprobar la solicitud: ' . $e->getMessage());
        }
    }

    public function rechazar($id)
    {
        $sol = SolicitudKit::findOrFail($id);
            DB::table('autorizaciones_kit')->insert([
            'id_solicitud_kit' => $id,
            'autorizado_por' => Auth::id(),
            'fecha_autorizacion' => now(),
            'estado' => 'Rechazada'
        ]);
        $sol->estado = 'Rechazada';
        $sol->save();
        return back()->with('success','Solicitud de kit rechazada');
    }
}
