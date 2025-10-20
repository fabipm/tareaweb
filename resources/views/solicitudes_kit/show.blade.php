@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white"><h4>Detalle Solicitud de Kit</h4></div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $sol->id_solicitud_kit }}</dd>

                        <dt class="col-sm-4">Kit</dt>
                        <dd class="col-sm-8">{{ $sol->kit->nombre_kit ?? '-' }}</dd>

                        <dt class="col-sm-4">Fecha</dt>
                        <dd class="col-sm-8">{{ $sol->fecha }}</dd>

                        <dt class="col-sm-4">Estado</dt>
                        <dd class="col-sm-8">{{ $sol->estado }}</dd>
                    </dl>

                    <h5>Componentes solicitados</h5>
                    @if($sol->componentes && $sol->componentes->count())
                        <ul class="list-group">
                        @foreach($sol->componentes as $c)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $c->componente->nombre_componente ?? 'Componente #' . $c->id_componente }}
                                <span class="badge bg-primary">Solicitado: {{ $c->cantidad_solicitada }} @if($c->cantidad_entregada) / Entregado: {{ $c->cantidad_entregada }} @endif</span>
                            </li>
                        @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No se solicitaron componentes adicionales.</p>
                    @endif

                    @if(Auth::user() && Auth::user()->rol === 'Estudiante')
                        <a href="{{ route('dashboard.estudiante') }}" class="btn btn-secondary mt-3">Volver</a>
                    @else
                        <a href="{{ route('dashboard.autorizar.kits') }}" class="btn btn-secondary mt-3">Volver</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
