<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SolicitudKit;
use App\Models\Kit;
use App\Models\SolicitudKitComponente;

class SolicitudKitController extends Controller
{
    public function index()
    {
        // Historial del usuario (estudiante)
        if (Auth::user()->rol === 'Estudiante') {
            $solicitudes = SolicitudKit::with(['kit','componentes'])->where('id_usuario', Auth::id())->orderBy('id_solicitud_kit','desc')->get();
            return view('solicitudes_kit.index', compact('solicitudes'));
        }

        // Admin: listar todas
        $solicitudes = SolicitudKit::with(['kit','usuario'])->orderBy('id_solicitud_kit','desc')->get();
        return view('solicitudes_kit.index', compact('solicitudes'));
    }

    public function create()
    {
        $kits = Kit::where('disponible',1)->get();
        // Obtener semestres disponibles tanto de solicitudes como de solicitudes_kit
        $sem1 = DB::table('solicitudes')->select('semestre_academico')->distinct()->pluck('semestre_academico')->toArray();
        $sem2 = DB::table('solicitudes_kit')->select('semestre_academico')->distinct()->pluck('semestre_academico')->toArray();
        $semestres = array_values(array_unique(array_merge($sem1, $sem2)));

        // Generar semestres próximos por defecto (año actual y siguiente), p.ej. 2025-I, 2025-II, 2026-I, 2026-II
        $currentYear = intval(date('Y'));
        $generated = [];
        for ($y = $currentYear; $y <= $currentYear + 1; $y++) {
            $generated[] = "$y-I";
            $generated[] = "$y-II";
        }

        // Fusionar generado con los existentes, eliminar duplicados
        $semestres = array_values(array_unique(array_merge($generated, $semestres)));

        // Ordenar por año descendente y semestre (II antes que I)
        usort($semestres, function($a, $b) {
            [$ay, $ap] = explode('-', $a . '-');
            [$by, $bp] = explode('-', $b . '-');
            $ay = intval($ay);
            $by = intval($by);
            if ($ay !== $by) return $by <=> $ay; // año descendente
            $order = ['I' => 1, 'II' => 2];
            $ap = strtoupper(trim($ap));
            $bp = strtoupper(trim($bp));
            $va = $order[$ap] ?? 0;
            $vb = $order[$bp] ?? 0;
            return $vb <=> $va; // II (2) antes que I (1)
        });

        // Obtener personal de soporte (usuarios con rol Administrador o personal de soporte)
        $soportes = DB::table('usuarios')->where('rol', 'Administrador')->get();

        return view('solicitudes_kit.create', compact('kits', 'semestres', 'soportes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kit' => 'required|exists:kits,id_kit',
            'semestre_academico' => 'required|string',
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'codigo_responsable' => 'nullable|string|max:50',
            'nombre_responsable' => 'nullable|string|max:150',
            'docente_responsable' => 'nullable|string|max:150',
            'curso' => 'nullable|string|max:120',
            'estado_kit' => 'nullable|string|max:60',
            'personal_soporte' => 'nullable|integer|exists:usuarios,id_usuario',
            'observacion' => 'nullable|string',
            'componentes' => 'nullable|array',
            'componentes.*' => 'integer|min:0',
            'integrantes' => 'nullable|array',
            'integrantes.*.codigo_estudiante' => 'nullable|string|max:20',
            'integrantes.*.nombre_estudiante' => 'nullable|string|max:100',
        ]);

        // Si el usuario indicó que quiere "Solo componentes específicos",
        // requerimos al menos un componente con cantidad > 0.
        if ((isset($validated['estado_kit']) && $validated['estado_kit'] === 'Solo componentes específicos')) {
            $comps = $request->input('componentes', []);
            $has = false;
            if (is_array($comps)) {
                foreach ($comps as $q) {
                    if (intval($q) > 0) { $has = true; break; }
                }
            }
            if (!$has) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['componentes' => 'Debe seleccionar al menos un componente con cantidad mayor que 0 cuando el estado del KIT es "Solo componentes específicos".']);
            }
        }

    Log::info('SolicitudKitController@store called', ['input' => $request->all()]);

    // Crear la solicitud dentro de una transacción para mayor seguridad
        try {
            DB::beginTransaction();

            // Si personal_soporte viene vacío (''), convertir a null para evitar errores de casting
            if (isset($validated['personal_soporte']) && $validated['personal_soporte'] === '') {
                $validated['personal_soporte'] = null;
            }

            $sol = new SolicitudKit();
            $sol->fill($validated);
            $sol->id_usuario = Auth::id();
            $sol->fecha_registro = now();
            $sol->save();

            // Guardar componentes opcionales (esperamos 'componentes' => [id_componente => cantidad])
            $comps = $request->input('componentes', []);
            foreach ($comps as $id_comp => $cantidad) {
                $q = intval($cantidad);
                if ($q > 0) {
                    SolicitudKitComponente::create([
                        'id_solicitud_kit' => $sol->id_solicitud_kit,
                        'id_componente' => $id_comp,
                        'cantidad_solicitada' => $q
                    ]);
                }
            }

            // Guardar integrantes del kit (esperamos array de filas con 'codigo_estudiante' y 'nombre_estudiante')
            $integrantes = $request->input('integrantes', []);
            if (is_array($integrantes) && count($integrantes) > 0) {
                $rows = [];
                foreach ($integrantes as $it) {
                    $code = trim($it['codigo_estudiante'] ?? '');
                    $name = trim($it['nombre_estudiante'] ?? '');
                    if ($code !== '' || $name !== '') {
                        $rows[] = [
                            'id_solicitud_kit' => $sol->id_solicitud_kit,
                            'codigo_estudiante' => $code ?: null,
                            'nombre_estudiante' => $name ?: null,
                        ];
                    }
                }
                if (!empty($rows)) {
                    DB::table('integrantes_kit')->insert($rows);
                }
            }

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->to('/dashboard-estudiante?view=create-kit&success=true');
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error('Error guardando solicitud kit: '.$ex->getMessage(), ['trace' => $ex->getTraceAsString(), 'input' => $request->all()]);
            return redirect()->back()->withInput()->withErrors(['general' => 'Error al guardar la solicitud. Consulte al administrador.']);
        }
    }

    public function show($id)
    {
        $sol = SolicitudKit::with(['kit','componentes.componente','usuario'])->findOrFail($id);
        return view('solicitudes_kit.show', compact('sol'));
    }
}
