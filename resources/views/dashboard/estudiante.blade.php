@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
    @include('dashboard.sidebar')
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="pt-3 pb-2 mb-3">
                <h2>Bienvenido, {{ Auth::user()->nombre_completo }}</h2>
                <p class="lead">Desde aquí puedes gestionar tus solicitudes de servidor y kits Arduino.</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('solicitudes.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-1"></i> Crear Solicitud (Servidor)
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-clock-history me-1"></i> Historial de Solicitudes (Servidor)
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('solicitudes.kit.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-cpu me-1"></i> Nueva Solicitud de Kit Arduino
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('solicitudes.kit.index') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-archive me-1"></i> Historial de Solicitudes (Kits)
                        </a>
                    </div>
                </div>
            </div>

            {{-- Renderizar la vista correspondiente si se navega a una acción específica --}}
            @if(isset($show) && $show === 'create')
                @includeIf('solicitudes.create', ['servidores' => $servidores, 'soportes' => $soportes ?? [], 'in_dashboard' => true])
            @elseif(isset($show) && $show === 'history')
                @includeIf('solicitudes._table', ['solicitudes' => $solicitudes ?? []])
            @elseif(isset($kits) && isset($show) && $show === 'create-kit')
                @includeIf('solicitudes_kit.create', ['kits' => $kits, 'in_dashboard' => true])
            @else
                {{-- Mostrar siempre el historial de solicitudes de kits debajo de los botones --}}
                @includeIf('solicitudes_kit._table', ['solicitudes' => $solicitudes ?? []])
            @endif
        </main>
    </div>
</div>
@endsection
