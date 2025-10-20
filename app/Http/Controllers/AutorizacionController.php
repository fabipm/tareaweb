<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Autorizacion;
use App\Models\Solicitud;
use Carbon\Carbon;

class AutorizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // Aprobar una solicitud: crear registro en autorizaciones y actualizar estado
    public function aprobar(Request $request, $id)
    {
        $sol = Solicitud::findOrFail($id);
        $sol->estado = 'Autorizada';
        $sol->save();

        $aut = Autorizacion::create([
            'id_solicitud' => $sol->id_solicitud,
            'autorizado_por' => Auth::id(),
            'observaciones' => $request->input('observaciones', null),
            'fecha_autorizacion' => Carbon::now(),
            // La tabla `autorizaciones` usa el enum ('Pendiente','Aprobada','Rechazada')
            'estado' => 'Aprobada',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Solicitud autorizada']);
        }
        return redirect()->back()->with('success', 'Solicitud autorizada');
    }

    // Rechazar una solicitud
    public function rechazar(Request $request, $id)
    {
        $sol = Solicitud::findOrFail($id);
        $sol->estado = 'Rechazada';
        $sol->save();

        $aut = Autorizacion::create([
            'id_solicitud' => $sol->id_solicitud,
            'autorizado_por' => Auth::id(),
            'observaciones' => $request->input('observaciones', null),
            'fecha_autorizacion' => Carbon::now(),
            'estado' => 'Rechazada',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Solicitud rechazada']);
        }
        return redirect()->back()->with('success', 'Solicitud rechazada');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
