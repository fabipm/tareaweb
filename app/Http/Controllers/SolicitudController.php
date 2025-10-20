<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                 $solicitudes = \App\Models\Solicitud::with('servidor')->get();
                 // Si la petición es AJAX devolvemos sólo el partial de la tabla
                 if (request()->ajax() || request()->wantsJson()) {
                        return view('solicitudes._table', compact('solicitudes'));
                 }
                 // Si no es AJAX, redirigimos al dashboard para que el historial se muestre en el panel
                 return redirect()->route('dashboard.estudiante', ['view' => 'history']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
           $servidores = \App\Models\Servidor::where('disponible', 1)->get();
           return view('solicitudes.create', compact('servidores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $validated = $request->validate([
              'semestre_academico' => 'required|string|max:10',
              'fecha' => 'required|date',
              'hora_entrada' => 'required',
              'hora_salida' => 'required',
              'id_servidor' => 'required|exists:servidores,id_servidor',
              'codigo_responsable' => 'required|string|max:20',
              'nombre_responsable' => 'required|string|max:120',
           ]);

           $solicitud = new \App\Models\Solicitud($validated);
       $solicitud->id_usuario = Auth::id();
           $solicitud->incluir_monitor = $request->has('incluir_monitor');
           $solicitud->incluir_teclado = $request->has('incluir_teclado');
           $solicitud->incluir_mouse = $request->has('incluir_mouse');
           $solicitud->save();
           
                        // Guardar integrantes si existen
                        if ($request->has('integrantes')) {
                               foreach ($request->input('integrantes') as $item) {
                                      if (!empty($item['codigo']) || !empty($item['nombre'])) {
                                             \App\Models\Integrante::create([
                                                    'id_solicitud' => $solicitud->id_solicitud,
                                                    'codigo_estudiante' => $item['codigo'] ?? null,
                                                    'nombre_estudiante' => $item['nombre'] ?? null,
                                             ]);
                                      }
                               }
                        }

                        // Si la petición es AJAX, devolver JSON con éxito
                        if ($request->ajax() || $request->wantsJson()) {
                               return response()->json([
                                      'success' => true,
                                      'message' => 'Solicitud registrada correctamente',
                                      'redirect' => route('dashboard.estudiante', ['view' => 'create', 'success' => 'true'])
                               ]);
                        }

                        return redirect()->to('/dashboard-estudiante?view=create&success=true');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
           $solicitud = \App\Models\Solicitud::with('servidor')->findOrFail($id);
           return view('solicitudes.show', compact('solicitud'));
    }

    // Edit and update actions removed — editing solicitudes is not allowed via the UI.

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
