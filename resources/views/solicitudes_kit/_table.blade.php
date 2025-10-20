<div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><h4>Solicitudes de Kits</h4></div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Semestre</th>
                    <th>Kit</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $s)
                <tr>
                    <td>{{ $s->id_solicitud_kit }}</td>
                    <td>{{ $s->semestre_academico }}</td>
                    <td>{{ $s->kit->nombre_kit ?? '-' }}</td>
                    <td>{{ $s->fecha }}</td>
                    <td><span class="badge bg-{{ $s->estado=='Pendiente'?'warning':($s->estado=='Autorizada'?'success':'danger') }}">{{ $s->estado }}</span></td>
                    <td>
                        <a href="{{ route('solicitudes.kit.show', $s->id_solicitud_kit) }}" class="btn btn-info btn-sm">Ver</a>
                        @if(Auth::user() && Auth::user()->rol === 'Administrador')
                        <form action="{{ route('reportes.kit.aprobar', $s->id_solicitud_kit) }}" method="POST" class="d-inline ms-1">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                        </form>
                        <form action="{{ route('reportes.kit.rechazar', $s->id_solicitud_kit) }}" method="POST" class="d-inline ms-1">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
