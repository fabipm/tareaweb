<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4>Solicitudes de Préstamo de Servidores</h4>
    </div>
    <div class="card-body">
    <!-- Botón de nueva solicitud eliminado según petición del usuario -->
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Responsable</th>
                    <th>Servidor</th>
                    <th>Semestre</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
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
                    <td>
                        <a href="{{ route('solicitudes.show', $solicitud) }}" class="btn btn-info btn-sm">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
