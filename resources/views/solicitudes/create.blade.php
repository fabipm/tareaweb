@php
    // Determina si se incluye dentro del dashboard (mostrar solo el formulario)
    $inDashboard = $in_dashboard ?? false;
    $servidores = $servidores ?? (isset($servidores) ? $servidores : []);
    $soportes = $soportes ?? (isset($soportes) ? $soportes : []);
@endphp

@if(!$inDashboard)
    @extends('layouts.app')

    @section('content')
    <div class="container-fluid">
        <div class="row">
            @include('dashboard.sidebar')
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class="pt-3 pb-2 mb-3">
                    <h2>Nueva Solicitud de Préstamo</h2>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar y mostrar toast
    const successToast = document.getElementById('successToast');
    if (successToast && window.location.search.includes('success=true')) {
        const toast = new bootstrap.Toast(successToast);
        toast.show();
    }
});</script>
@endif

<!-- Formulario único para crear solicitud -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4>Nueva Solicitud de Préstamo</h4>
    </div>

    <!-- Toast de Éxito -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center">
                <div class="toast-body d-flex align-items-center">
                    <svg class="me-2" style="width:24px;height:24px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>¡Solicitud enviada con éxito!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Estilos para el toast -->
    <style>
    .toast {
        opacity: 1 !important;
    }
    .toast.show {
        opacity: 1 !important;
    }
    .toast .toast-body {
        padding: 0.75rem 0.5rem;
    }
    </style>

    <div class="card-body">
    <form id="solicitud-form" method="POST" action="{{ route('solicitudes.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="docente_responsable" class="form-label">Docente Responsable</label>
                    <input type="text" name="docente_responsable" class="form-control" placeholder="Nombre del docente responsable">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="curso" class="form-label">Curso</label>
                    <input type="text" name="curso" class="form-control" placeholder="Curso (ej. Redes I)">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="codigo_responsable" class="form-label">Código del Responsable</label>
                    <input type="text" name="codigo_responsable" class="form-control" value="{{ Auth::user()->codigo_usuario }}" readonly required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nombre_responsable" class="form-label">Nombre del Responsable</label>
                    <input type="text" name="nombre_responsable" class="form-control" value="{{ Auth::user()->nombre_completo }}" readonly required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="semestre_academico" class="form-label">Semestre Académico</label>
                    <select name="semestre_academico" class="form-select" required>
                        <option value="2025-I">2025-I</option>
                        <option value="2025-II">2025-II</option>
                        <option value="2026-I">2026-I</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="hora_entrada" class="form-label">Hora entrada</label>
                    <input type="time" name="hora_entrada" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="hora_salida" class="form-label">Hora salida</label>
                    <input type="time" name="hora_salida" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_servidor" class="form-label">Servidor</label>
                    <select name="id_servidor" id="select-servidor" class="form-select" required>
                        <option value="">-- Seleccionar servidor --</option>
                        @foreach($servidores as $servidor)
                            <option value="{{ $servidor->id_servidor }}" data-serie="{{ $servidor->serie_servidor ?? '' }}" data-tipo="{{ $servidor->tipo_servidor ?? '' }}" data-caracteristicas="{{ $servidor->caracteristicas ?? '' }}">{{ $servidor->nombre_servidor }} ({{ $servidor->tipo_servidor }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Detalles del servidor</label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" id="serie_servidor" class="form-control" placeholder="Serie" readonly>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="tipo_servidor" class="form-control" placeholder="Tipo" readonly>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="caracteristicas_servidor" class="form-control" placeholder="Características" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Incluir:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="incluir_monitor" value="1">
                    <label class="form-check-label">Monitor</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="incluir_teclado" value="1">
                    <label class="form-check-label">Teclado</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="incluir_mouse" value="1">
                    <label class="form-check-label">Mouse</label>
                </div>
            </div>

            {{-- Los campos de responsable se muestran arriba (rellenados con el usuario autenticado) --}}

            <div class="mb-3">
                <label class="form-label">Integrantes del Grupo</label>
                <div id="integrantes-list">
                    <!-- Se añadirán dinámicamente -->
                </div>
                <button type="button" id="add-integrante" class="btn btn-success btn-sm mt-2">Agregar integrante</button>
            </div>

            <div class="mb-3">
                <label for="personal_soporte" class="form-label">Personal de Soporte</label>
                <select name="personal_soporte" class="form-select">
                    <option value="">-- Seleccionar --</option>
                    @if(!empty($soportes))
                        @foreach($soportes as $s)
                            <option value="{{ $s->id_usuario }}">{{ $s->nombre_completo }}</option>
                        @endforeach
                    @endif
                        <!-- Opciones adicionales estáticas solicitadas -->
                        <option value="soporte1">Soporte - Julio Pérez</option>
                        <option value="soporte2">Soporte - María López</option>
                        <option value="soporte3">Soporte - Carlos Gómez</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Registrar Solicitud</button>
            <a href="{{ route('dashboard.estudiante') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

@if(!$inDashboard)
            </main>
        </div>
    </div>
    @endsection
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectServidor = document.getElementById('select-servidor');
    const serieServidor = document.getElementById('serie_servidor');
    const tipoServidor = document.getElementById('tipo_servidor');
    const caracteristicasServidor = document.getElementById('caracteristicas_servidor');

    // Manejar cambio en la selección del servidor
    selectServidor.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Obtener datos del servidor seleccionado desde los data attributes
            const serie = selectedOption.getAttribute('data-serie') || '';
            const tipo = selectedOption.getAttribute('data-tipo') || '';
            const caracteristicas = selectedOption.getAttribute('data-caracteristicas') || '';
            
            // Llenar los campos de detalles del servidor
            serieServidor.value = serie;
            tipoServidor.value = tipo;
            caracteristicasServidor.value = caracteristicas;
        } else {
            // Limpiar los campos si no hay servidor seleccionado
            serieServidor.value = '';
            tipoServidor.value = '';
            caracteristicasServidor.value = '';
        }
    });

    // Funcionalidad para agregar integrantes
    const integrantesList = document.getElementById('integrantes-list');
    const addIntegranteBtn = document.getElementById('add-integrante');
    let integranteCount = 0;

    addIntegranteBtn.addEventListener('click', function() {
        integranteCount++;
        const integranteDiv = document.createElement('div');
        integranteDiv.className = 'row mb-2 integrante-row';
        integranteDiv.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="integrantes[${integranteCount}][codigo]" class="form-control" placeholder="Código del integrante" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="integrantes[${integranteCount}][nombre]" class="form-control" placeholder="Nombre completo del integrante" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-integrante">×</button>
            </div>
        `;
        integrantesList.appendChild(integranteDiv);

        // Agregar evento para remover integrante
        integranteDiv.querySelector('.remove-integrante').addEventListener('click', function() {
            integranteDiv.remove();
        });
    });
});
</script>
