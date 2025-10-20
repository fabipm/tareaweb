@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('dashboard.sidebar')
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="pt-3">
                <h1 class="h3">Reportes y Estadísticas</h1>
                <p class="text-muted">Panel de métricas e informes sobre solicitudes de préstamo</p>
            </div>

            <div class="row my-3">
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Total</small>
                            <h3 class="mt-2">{{ \App\Models\Solicitud::count() }}</h3>
                            <div class="text-muted">Solicitudes registradas</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Pendientes</small>
                            <h3 class="mt-2">{{ \App\Models\Solicitud::where('estado', 'Pendiente')->count() }}</h3>
                            <div class="text-muted">Solicitudes por revisar</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Autorizadas</small>
                            <h3 class="mt-2">{{ \App\Models\Solicitud::where('estado', 'Autorizada')->count() }}</h3>
                            <div class="text-muted">Solicitudes procesadas</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Rechazadas</small>
                            <h3 class="mt-2">{{ \App\Models\Solicitud::where('estado', 'Rechazada')->count() }}</h3>
                            <div class="text-muted">Solicitudes rechazadas</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form id="report-filters" class="row g-2 align-items-end" method="GET" action="{{ route('reportes.index') }}">
                        <div class="col-md-3">
                            <label class="form-label small">Semestre</label>
                            <select name="semestre" class="form-select">
                                <option value="">Todos los semestres</option>
                                @php
                                    $semestres = \App\Models\Solicitud::select('semestre_academico')->distinct()->orderBy('semestre_academico','desc')->pluck('semestre_academico');
                                @endphp
                                @foreach($semestres as $sem)
                                    <option value="{{ $sem }}" {{ (!empty($filtros['semestre']) && $filtros['semestre'] == $sem) ? 'selected' : '' }}>{{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                @foreach(['Pendiente','Autorizada','Rechazada'] as $est)
                                    <option value="{{ $est }}" {{ (!empty($filtros['estado']) && $filtros['estado'] == $est) ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                            <a href="{{ route('reportes.index') }}" class="btn btn-success">Exportar</a>
                            <a href="{{ route('dashboard.admin') }}" class="btn btn-secondary">Limpiar</a>
                            <button type="button" class="btn btn-outline-primary" onclick="location.reload();">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Semestre</th>
                                    <th>Docente</th>
                                    <th>Curso</th>
                                    <th>Fecha</th>
                                    <th>Servidor</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="report-body">
                                @php
                                    if (!isset($solicitudes)) {
                                        $solicitudes = \App\Models\Solicitud::with(['servidor'])->orderBy('id_solicitud', 'desc')->get();
                                    }
                                @endphp
                                @forelse($solicitudes as $s)
                                    <tr>
                                        <td>{{ $s->id_solicitud }}</td>
                                        <td>{{ $s->semestre_academico ?? '-' }}</td>
                                        <td>{{ $s->docente_responsable ?? $s->nombre_responsable ?? '-' }}</td>
                                        <td>{{ $s->curso ?? '-' }}</td>
                                        <td>{{ $s->fecha }}</td>
                                        <td>{{ $s->servidor->nombre_servidor ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $s->estado == 'Pendiente' ? 'warning' : ($s->estado == 'Autorizada' ? 'success' : 'danger') }}">{{ $s->estado ?? 'Pendiente' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('solicitudes.show', $s) }}" class="btn btn-info btn-sm">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No hay solicitudes para mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
