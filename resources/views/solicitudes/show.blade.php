@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4>Detalle de Solicitud</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Información básica</h5>
                        <dl class="row">
                            <dt class="col-sm-4">Código Responsable</dt>
                            <dd class="col-sm-8">{{ $solicitud->codigo_responsable ?? '-' }}</dd>

                            <dt class="col-sm-4">Nombre Responsable</dt>
                            <dd class="col-sm-8">{{ $solicitud->nombre_responsable ?? '-' }}</dd>

                            <dt class="col-sm-4">Docente Responsable</dt>
                            <dd class="col-sm-8">{{ $solicitud->docente_responsable ?? '-' }}</dd>

                            <dt class="col-sm-4">Curso</dt>
                            <dd class="col-sm-8">{{ $solicitud->curso ?? '-' }}</dd>

                            <dt class="col-sm-4">Creado por</dt>
                            <dd class="col-sm-8">{{ $solicitud->usuario->nombre_completo ?? 'Usuario #' . ($solicitud->id_usuario ?? '-') }}</dd>

                            <dt class="col-sm-4">Fecha registro</dt>
                            <dd class="col-sm-8">{{ $solicitud->fecha_registro ?? $solicitud->created_at ?? '-' }}</dd>
                        </dl>
                    </div>

                    <div class="mb-3">
                        <h5>Detalles del servidor</h5>
                        <dl class="row">
                            <dt class="col-sm-4">Servidor</dt>
                            <dd class="col-sm-8">{{ $solicitud->servidor->nombre_servidor ?? '-' }}</dd>

                            <dt class="col-sm-4">Serie</dt>
                            <dd class="col-sm-8">{{ $solicitud->servidor->serie_servidor ?? '-' }}</dd>

                            <dt class="col-sm-4">Tipo</dt>
                            <dd class="col-sm-8">{{ $solicitud->servidor->tipo_servidor ?? '-' }}</dd>

                            <dt class="col-sm-4">Características</dt>
                            <dd class="col-sm-8">{{ $solicitud->servidor->caracteristicas ?? '-' }}</dd>

                            <dt class="col-sm-4">Semestre</dt>
                            <dd class="col-sm-8">{{ $solicitud->semestre_academico }}</dd>

                            <dt class="col-sm-4">Fecha</dt>
                            <dd class="col-sm-8">{{ $solicitud->fecha }}</dd>

                            <dt class="col-sm-4">Hora entrada</dt>
                            <dd class="col-sm-8">{{ $solicitud->hora_entrada }}</dd>

                            <dt class="col-sm-4">Hora salida</dt>
                            <dd class="col-sm-8">{{ $solicitud->hora_salida }}</dd>
                        </dl>
                    </div>

                    <div class="mb-3">
                        <h5>Equipos y extras</h5>
                        <p>
                            @if($solicitud->incluir_monitor) <span class="badge bg-secondary">Monitor</span> @endif
                            @if($solicitud->incluir_teclado) <span class="badge bg-secondary">Teclado</span> @endif
                            @if($solicitud->incluir_mouse) <span class="badge bg-secondary">Mouse</span> @endif
                            @if(!$solicitud->incluir_monitor && !$solicitud->incluir_teclado && !$solicitud->incluir_mouse)
                                <span class="text-muted">Ninguno</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <h5>Integrantes</h5>
                        @if($solicitud->integrantes && $solicitud->integrantes->count())
                            <ul class="list-group">
                                @foreach($solicitud->integrantes as $int)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $int->codigo_estudiante ?? '–' }}</strong>
                                            <div class="text-muted">{{ $int->nombre_estudiante ?? 'Sin nombre' }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No se registraron integrantes.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h5>Autorizaciones</h5>
                        @if($solicitud->autorizaciones && $solicitud->autorizaciones->count())
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Autor</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitud->autorizaciones as $aut)
                                        <tr>
                                            <td>{{ $aut->usuario?->nombre_completo ?? ($aut->id_usuario ?? '–') }}</td>
                                            <td>{{ $aut->fecha ?? '-' }}</td>
                                            <td>{{ $aut->estado ?? '-' }}</td>
                                            <td>{{ $aut->observacion ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No hay autorizaciones registradas.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h5>Información administrativa</h5>
                        <dl class="row">
                            <dt class="col-sm-4">Personal de Soporte</dt>
                            <dd class="col-sm-8">{{ $solicitud->personal_soporte ?? '-' }}</dd>

                            <dt class="col-sm-4">Estado</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-{{ $solicitud->estado == 'Pendiente' ? 'warning' : ($solicitud->estado == 'Autorizada' ? 'success' : 'danger') }}">
                                    {{ $solicitud->estado }}
                                </span>
                            </dd>

                            <dt class="col-sm-4">Observación</dt>
                            <dd class="col-sm-8">{{ $solicitud->observacion ?? '-' }}</dd>
                        </dl>
                    </div>
                    @php
                        // Determinar la ruta de dashboard según el rol del usuario
                        $dashboardUrl = route('dashboard.estudiante');
                        if (auth()->check()) {
                            $rol = auth()->user()->rol ?? '';
                            if ($rol === 'Estudiante') {
                                $dashboardUrl = route('dashboard.estudiante');
                            } elseif ($rol === 'Administrador') {
                                $dashboardUrl = route('dashboard.admin');
                            } else {
                                // fallback por defecto
                                $dashboardUrl = route('dashboard.estudiante');
                            }
                        }
                    @endphp
                    <a href="{{ $dashboardUrl }}" class="btn btn-secondary"
                       onclick="(function(e){
                           try {
                               if (document.referrer && (new URL(document.referrer)).origin === location.origin) {
                                   e.preventDefault();
                                   history.back();
                                   return false;
                               }
                           } catch (err) {
                               // Si falla, seguir al href por defecto
                           }
                       })(event)">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
