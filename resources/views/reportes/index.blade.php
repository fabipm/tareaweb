@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4>Reporte de Préstamos de Servidores</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reportes.index') }}" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha inicio" value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha fin" value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="estado" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="Pendiente" @if(request('estado')=='Pendiente') selected @endif>Pendiente</option>
                                    <option value="Autorizada" @if(request('estado')=='Autorizada') selected @endif>Autorizada</option>
                                    <option value="Rechazada" @if(request('estado')=='Rechazada') selected @endif>Rechazada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Responsable</th>
                                <th>Servidor</th>
                                <th>Semestre</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud->id_solicitud }}</td>
                                <td>{{ $solicitud->nombre_responsable }}</td>
                                <td>{{ $solicitud->servidor->nombre_servidor ?? '-' }}</td>
                                <td>{{ $solicitud->semestre_academico }}</td>
                                <td>{{ $solicitud->fecha }}</td>
                                <td>
                                    <span class="badge bg-{{ $solicitud->estado == 'Pendiente' ? 'warning' : ($solicitud->estado == 'Autorizada' ? 'success' : 'danger') }}">
                                        {{ $solicitud->estado }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay registros para los filtros seleccionados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
