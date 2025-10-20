<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Solicitud;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = Solicitud::with('servidor');

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('semestre')) {
            $query->where('semestre_academico', $request->semestre);
        }

        $solicitudes = $query->orderBy('fecha', 'desc')->get();
        // Pasamos al mismo layout del admin para mantener consistencia visual
        return view('dashboard.admin', [
            'solicitudes' => $solicitudes,
            'filtros' => $request->only(['semestre', 'estado', 'fecha_inicio', 'fecha_fin'])
        ]);
    }
}
