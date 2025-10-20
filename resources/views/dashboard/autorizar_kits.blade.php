@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('dashboard.sidebar')
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="pt-3">
                <h1 class="h3">Autorización de Solicitudes (Kits)</h1>
                <p class="text-muted">Revisa y autoriza o rechaza solicitudes de kits pendientes.</p>
            </div>

            <div class="row my-3">
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center">
                            <small class="text-muted">Total</small>
                            <h3 class="mt-2">{{ $total ?? 0 }}</h3>
                            <div class="text-muted">Total de Solicitudes de Kits</div>
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
                                    <th>Kit</th>
                                    <th>Docente</th>
                                    <th>Semestre</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes as $s)
                                    <tr>
                                        <td>{{ $s->id_solicitud_kit }}</td>
                                        <td>{{ $s->kit->nombre_kit ?? '-' }}</td>
                                        <td>{{ $s->docente_responsable ?? $s->nombre_responsable }}</td>
                                        <td>{{ $s->semestre_academico ?? '-' }}</td>
                                        <td>{{ $s->fecha }}</td>
                                        <td><span class="badge bg-warning">{{ $s->estado }}</span></td>
                                        <td>
                                            <a href="{{ route('solicitudes.kit.show', $s) }}" class="btn btn-info btn-sm">Detalles</a>
                                            <form action="{{ route('reportes.kit.aprobar', $s->id_solicitud_kit) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                            </form>
                                            <form action="{{ route('reportes.kit.rechazar', $s->id_solicitud_kit) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay solicitudes de kits pendientes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- Toast de éxito -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex align-items-center">
                    <div class="toast-body d-flex align-items-center">
                        <svg class="me-2" style="width:24px;height:24px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span id="toastMsg">Acción realizada con éxito</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <!-- Toast de error -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex align-items-center">
                    <div class="toast-body d-flex align-items-center">
                        <svg class="me-2" style="width:24px;height:24px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span id="toastErrorMsg">Ocurrió un error</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <style>
        .toast { opacity: 1 !important; }
        .toast.show { opacity: 1 !important; }
        .toast .toast-body { padding: 0.75rem 0.5rem; }
        </style>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMsg = "{{ session('success') }}";
            var errorMsg = "{{ session('error') }}";
            if (successMsg) {
                document.getElementById('toastMsg').textContent = successMsg;
                new bootstrap.Toast(document.getElementById('successToast')).show();
                setTimeout(function(){ location.reload(); }, 2000);
            }
            if (errorMsg) {
                document.getElementById('toastErrorMsg').textContent = errorMsg;
                new bootstrap.Toast(document.getElementById('errorToast')).show();
                setTimeout(function(){ location.reload(); }, 2000);
            }
        });
        </script>
        </main>
    </div>
</div>
@endsection
