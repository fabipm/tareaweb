<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function estudiante(Request $request)
    {
        // Obtener servidores disponibles para el formulario de creación si se pide
        $show = $request->query('view');
        $servidores = [];
        $soportes = [];
    $kits = [];
        if ($show === 'create') {
            $servidores = \App\Models\Servidor::where('disponible', 1)->get();
            // Obtener usuarios con rol 'Soporte' para el select de personal de soporte
            $soportes = \App\Models\Usuario::where('rol', 'Soporte')->where('estado', 1)->get();
        }
        // cargar form de kits
        if ($show === 'create-kit') {
            $kits = \App\Models\Kit::where('disponible', 1)->get();
            // Obtener personal de soporte para el select en el formulario de kits
            $soportes = \App\Models\Usuario::where('rol', 'Soporte')->where('estado', 1)->get();
        }
        if ($show === 'history') {
            // Mostrar solo las solicitudes del usuario autenticado
            $solicitudes = \App\Models\Solicitud::with('servidor')
                ->where('id_usuario', Auth::id())
                ->orderBy('id_solicitud', 'desc')
                ->get();
        } elseif ($show === 'history-kit') {
            $solicitudes = \App\Models\SolicitudKit::with('kit')
                ->where('id_usuario', Auth::id())
                ->orderBy('id_solicitud_kit','desc')
                ->get();
        } else {
            $solicitudes = [];
        }
        return view('dashboard.estudiante', compact('show', 'servidores', 'soportes', 'solicitudes','kits'));
    }

    public function admin()
    {
        return view('dashboard.admin');
    }

    public function autorizar()
    {
        // métricas y solicitudes pendientes
        $total = \App\Models\Solicitud::count();
        $pendientes = \App\Models\Solicitud::where('estado', 'Pendiente')->count();
        $autorizadas = \App\Models\Solicitud::where('estado', 'Autorizada')->count();
        $rechazadas = \App\Models\Solicitud::where('estado', 'Rechazada')->count();
        $solicitudes = \App\Models\Solicitud::with('servidor')->where('estado','Pendiente')->orderBy('id_solicitud','desc')->get();
        return view('dashboard.autorizar', compact('total','pendientes','autorizadas','rechazadas','solicitudes'));
    }

    public function autorizarKits()
    {
        // métricas y solicitudes pendientes para kits
        $total = \App\Models\SolicitudKit::count();
        $pendientes = \App\Models\SolicitudKit::where('estado', 'Pendiente')->count();
        $autorizadas = \App\Models\SolicitudKit::where('estado', 'Autorizada')->count();
        $rechazadas = \App\Models\SolicitudKit::where('estado', 'Rechazada')->count();
        $solicitudes = \App\Models\SolicitudKit::with('kit')->where('estado','Pendiente')->orderBy('id_solicitud_kit','desc')->get();
        return view('dashboard.autorizar_kits', compact('total','pendientes','autorizadas','rechazadas','solicitudes'));
    }

    public function reportesKits()
    {
        // Básicos: conteo de kits, stock total, solicitudes por mes (últimos 12 meses)
        $totalKits = \App\Models\Kit::count();
        $stockTotal = \App\Models\Kit::sum('stock_disponible');

        // Solicitudes por mes (últimos 12 meses)
        $raw = DB::table('solicitudes_kit')
            ->select(DB::raw("DATE_FORMAT(fecha_registro, '%Y-%m') as ym"), DB::raw('count(*) as total'))
            ->where('fecha_registro', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Generar etiquetas de los últimos 12 meses
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        // Mapear resultados a un arreglo con ceros por defecto
        $monthMap = [];
        foreach ($raw as $r) {
            $monthMap[$r->ym] = intval($r->total);
        }
        $monthCounts = array_map(function ($m) use ($monthMap) {
            return $monthMap[$m] ?? 0;
        }, $months);

        $solicitudesRecientes = \App\Models\SolicitudKit::with('kit')
            ->orderBy('fecha_registro','desc')
            ->limit(10)
            ->get();
        // Top kits por número de solicitudes (top 5)
        $topKits = DB::table('solicitudes_kit')
            ->join('kits', 'solicitudes_kit.id_kit', '=', 'kits.id_kit')
            ->select('kits.nombre_kit', DB::raw('count(*) as total'))
            ->groupBy('solicitudes_kit.id_kit', 'kits.nombre_kit')
            ->orderBy('total','desc')
            ->limit(5)
            ->get();
    $solPorMes = $raw;
    return view('dashboard.reportes_kits', compact('totalKits','stockTotal','raw','months','monthCounts','solicitudesRecientes','topKits','solPorMes'));
    }
}
