@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('dashboard.sidebar')
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
            <div class="pt-3">
                <h1 class="h3">Reportes Kits</h1>
                <p class="text-muted">Resumen y métricas relacionadas con los kits.</p>
            </div>

            <div class="row my-3">
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3 border-success">
                        <div class="card-body text-center">
                            <small class="text-success">Total de Kits</small>
                            <h3 class="mt-2 text-success">{{ $totalKits ?? 0 }}</h3>
                            <div class="text-muted">Kits registrados</div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de Stock disponible eliminada -->
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3 border-warning">
                        <div class="card-body text-center">
                            <small class="text-warning">Solicitudes últimos 12 meses</small>
                            <h3 class="mt-2 text-warning">{{ array_sum($monthCounts ?? []) }}</h3>
                            <div class="text-muted">Solicitudes totales</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3 border-info">
                        <div class="card-body text-center">
                            <small class="text-info">Solicitudes recientes</small>
                            <h3 class="mt-2 text-info">{{ isset($solicitudesRecientes) ? count($solicitudesRecientes) : 0 }}</h3>
                            <div class="text-muted">Últimos 10 registros</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Solicitudes por mes</h5>
                            <canvas id="chartMonthly" height="120" data-months='@json($months)' data-counts='@json($monthCounts)'></canvas>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Mes</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($solPorMes as $row)
                                            <tr>
                                                <td>{{ $row->ym }}</td>
                                                <td>{{ $row->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Top Kits</h5>
                            <canvas id="chartTopKits" height="120" data-labels='@json($topKits->pluck("nombre_kit"))' data-values='@json($topKits->pluck("total"))'></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Solicitudes recientes</h5>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($solicitudesRecientes ?? [] as $s)
                                            <tr>
                                                <td>{{ $s->id_solicitud_kit }}</td>
                                                <td>{{ $s->kit->nombre_kit ?? '-' }}</td>
                                                <td>{{ $s->docente_responsable ?? $s->nombre_responsable }}</td>
                                                <td>{{ $s->semestre_academico ?? '-' }}</td>
                                                <td>{{ $s->fecha }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $s->estado == 'Pendiente' ? 'warning' : ($s->estado == 'Autorizada' ? 'success' : 'danger') }}">{{ $s->estado }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No hay registros recientes.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const canvas = document.getElementById('chartMonthly');
                    const months = JSON.parse(canvas.getAttribute('data-months') || '[]');
                    const monthCounts = JSON.parse(canvas.getAttribute('data-counts') || '[]');

                    const ctx = document.getElementById('chartMonthly').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Solicitudes',
                                data: monthCounts,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            }]
                        },
                        options: { responsive: true }
                    });

                    const canvas2 = document.getElementById('chartTopKits');
                    const topKits = canvas2 ? JSON.parse(canvas2.getAttribute('data-labels') || '[]') : [];
                    const topCounts = canvas2 ? JSON.parse(canvas2.getAttribute('data-values') || '[]') : [];
                    const ctx2 = document.getElementById('chartTopKits').getContext('2d');
                    new Chart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: topKits,
                            datasets: [{
                                data: topCounts,
                                backgroundColor: [
                                    '#4CAF50','#2196F3','#FFC107','#FF5722','#9C27B0'
                                ]
                            }]
                        },
                        options: { responsive: true }
                    });
                });
            </script>
        </main>
    </div>
</div>
@endsection
