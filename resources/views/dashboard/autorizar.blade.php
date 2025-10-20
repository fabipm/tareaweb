@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('dashboard.sidebar')
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="pt-3">
                <h1 class="h3">Autorización de Solicitudes</h1>
                <p class="text-muted">Revisa y autoriza o rechaza solicitudes pendientes.</p>
            </div>

            <div class="row my-3">
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Total</small>
                            <h3 class="mt-2">{{ $total ?? 0 }}</h3>
                            <div class="text-muted">Total de Solicitudes</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Pendientes</small>
                            <h3 class="mt-2">{{ $pendientes ?? 0 }}</h3>
                            <div class="text-muted">Solicitudes pendientes</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Autorizadas</small>
                            <h3 class="mt-2">{{ $autorizadas ?? 0 }}</h3>
                            <div class="text-muted">Autorizadas</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Rechazadas</small>
                            <h3 class="mt-2">{{ $rechazadas ?? 0 }}</h3>
                            <div class="text-muted">Rechazadas</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Docente</th>
                                    <th>Curso</th>
                                    <th>Semestre</th>
                                    <th>Fecha</th>
                                    <th>Servidor</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes as $s)
                                    <tr>
                                        <td>{{ $s->id_solicitud }}</td>
                                        <td>{{ $s->docente_responsable ?? $s->nombre_responsable }}</td>
                                        <td>{{ $s->curso ?? '-' }}</td>
                                        <td>{{ $s->semestre_academico ?? '-' }}</td>
                                        <td>{{ $s->fecha }}</td>
                                        <td>{{ $s->servidor->nombre_servidor ?? '-' }}</td>
                                        <td><span class="badge bg-warning">{{ $s->estado }}</span></td>
                                        <td>
                                            <a href="{{ route('solicitudes.show', $s) }}" class="btn btn-info btn-sm">Detalles</a>
                                            <form action="{{ route('reportes.aprobar', $s->id_solicitud) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                            </form>
                                            <form action="{{ route('reportes.rechazar', $s->id_solicitud) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay solicitudes pendientes.</td>
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
