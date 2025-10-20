@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active ajax-load" href="{{ route('dashboard.estudiante', ['view' => 'create']) }}">Crear Solicitud (Servidor)</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link p-0" onclick="return loadAjax(event, '{{ route('dashboard.estudiante', ['view' => 'history']) }}');">Historial de Solicitudes (Servidor)</button>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link ajax-load" href="{{ route('dashboard.estudiante', ['view' => 'create-kit']) }}">Nueva Solicitud de Kit Arduino</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link p-0" onclick="return loadAjax(event, '{{ route('dashboard.estudiante', ['view' => 'history-kit']) }}');">Historial de Solicitudes (Kits)</button>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="{{ route('logout') }}">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="pt-3 pb-2 mb-3">
                <h2>Bienvenido, {{ Auth::user()->nombre_completo }}</h2>
                <p class="lead">Desde aquí puedes solicitar un préstamo de servidor y ver tu historial.</p>
            </div>

            @if(isset($show) && $show === 'create')
                {{-- Incluir el formulario de creación de servidor directamente en el panel principal --}}
                @includeIf('solicitudes.create', ['servidores' => $servidores, 'soportes' => $soportes ?? [], 'in_dashboard' => true])
            @elseif(isset($show) && $show === 'history')
                {{-- Incluir el historial de servidores --}}
                @includeIf('solicitudes._table', ['solicitudes' => $solicitudes ?? []])
            @elseif(isset($kits) && isset($show) && $show === 'create-kit')
                @includeIf('solicitudes_kit.create', ['kits' => $kits, 'in_dashboard' => true])
            @elseif(isset($show) && $show === 'history-kit')
                @includeIf('solicitudes_kit._table', ['solicitudes' => $solicitudes ?? []])
            @else
                {{-- Si la ruta cargó directamente las vistas de kit (por ajax), dejar que las vistas lo manejen --}}
                @if(View::exists('solicitudes_kit.index') && isset($solicitudes) && request()->is('solicitudes-kit'))
                    @includeIf('solicitudes_kit.index', ['solicitudes' => $solicitudes])
                @endif
            @endif
        </main>
    </div>
</div>
@endsection
