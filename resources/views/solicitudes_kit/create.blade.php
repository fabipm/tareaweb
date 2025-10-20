@php
    // Determina si se incluye dentro del dashboard (mostrar solo el formulario)
    $inDashboard = $in_dashboard ?? false;
    $kits = $kits ?? (isset($kits) ? $kits : []);
    // Asegurar que $soportes esté inicializado (se espera un array de usuarios disponibles)
    $soportes = $soportes ?? (isset($soportes) ? $soportes : []);
// Nota: formulario actualizado para incluir `id_kit`, `observacion` y `id_usuario` oculto
// que existen en la tabla `solicitudes_kit` de la BD. Mantener consistencia con el
// controlador `SolicitudKitController@store` para guardar los campos.
@endphp

@if(!($in_dashboard ?? false))
    @extends('layouts.app')

    @section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
@endif

<div class="card shadow-sm">
    <div class="card-header bg-success text-white"><h4>Solicitud de Préstamo de Kit Arduino</h4></div>
    <div class="card-body">
        <form id="solicitud-kit-form" method="POST" action="{{ route('solicitudes.kit.store') }}">
            @csrf

            {{-- Mostrar errores globales de validación --}}
            @if($errors->any())

                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Error específico para componentes (validación condicional) --}}
            @error('componentes')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Tema / Proyecto</label>
                    <input type="text" name="tema_proyecto" class="form-control" placeholder="Tema o proyecto (opcional)" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Semestre Académico</label>
                    @php
                        // Generar semestres por defecto y fusionarlos con los recibidos desde el controlador
                        $generated = [];
                        $currentYear = intval(date('Y'));
                        for ($y = $currentYear; $y <= $currentYear + 1; $y++) {
                            $generated[] = $y . '-I';
                            $generated[] = $y . '-II';
                        }
                        $options = [];
                        if (!empty($semestres) && is_array($semestres)) {
                            $options = array_values(array_unique(array_merge($generated, $semestres)));
                        } else {
                            $options = $generated;
                        }
                    @endphp
                    <select name="semestre_academico" class="form-select" required>
                        <option value="">-- Seleccionar semestre --</option>
                        @foreach($options as $opt)
                            <option value="{{ $opt }}" @selected(old('semestre_academico') == $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hora entrada</label>
                    <input type="time" name="hora_entrada" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hora salida</label>
                    <input type="time" name="hora_salida" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Docente Responsable</label>
                    <input type="text" name="docente_responsable" class="form-control" placeholder="Nombre del docente responsable" />
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Curso</label>
                    <input type="text" name="curso" class="form-control" placeholder="Nombre o código del curso" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kit Arduino</label>
                    <select name="id_kit" class="form-select" required>
                        <option value="">-- Seleccionar Kit --</option>
                        @foreach($kits as $k)
                            <option value="{{ $k->id_kit }}">{{ $k->nombre_kit }} @if(!empty($k->codigo_kit)) ({{ $k->codigo_kit }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estado del KIT</label>
                    <select name="estado_kit" class="form-select">
                        <option value="Completo">Completo</option>
                        <option value="Solo componentes específicos">Solo componentes específicos</option>
                    </select>
                </div>
            </div>

            <div id="componentes-area" class="mb-3">
                <h5>Componentes (opcional)</h5>
                <p class="text-muted">Si quieres, selecciona cantidades de componentes adicionales o menores al kit estándar.</p>
                <div id="componentes-list">
                    {{-- Será cargado dinámicamente vía JS si es necesario --}}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Código del Responsable</label>
                    <input type="text" name="codigo_responsable" class="form-control" value="{{ Auth::user()->codigo_usuario ?? '' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nombre del Responsable</label>
                    <input type="text" name="nombre_responsable" class="form-control" value="{{ Auth::user()->nombre_completo ?? '' }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Integrantes del equipo</label>
                <div id="integrantes-list">
                    <!-- inputs añadidos dinámicamente -->
                </div>
                <button type="button" id="add-integrante-kit" class="btn btn-outline-primary btn-sm mt-2">Agregar integrante</button>
            </div>

            <div class="mb-3">
                <label class="form-label">Observación / Comentarios</label>
                <textarea name="observacion" class="form-control" rows="3" placeholder="Información adicional sobre la solicitud (opcional)"></textarea>
            </div>

            {{-- Campo oculto con id del usuario que hace la solicitud (se puede setear en el controlador si se prefiere) --}}
            <input type="hidden" name="id_usuario" value="{{ Auth::user()->id_usuario ?? Auth::id() }}">

            <div class="mb-3">
                <label for="personal_soporte" class="form-label">Personal de Soporte</label>
                <select name="personal_soporte" class="form-select">
                    <option value="">-- Seleccionar --</option>
                    @if(!empty($soportes) && count($soportes) > 0)
                        @foreach($soportes as $s)
                            <option value="{{ $s->id_usuario }}" @selected(old('personal_soporte') == $s->id_usuario)>{{ $s->nombre_completo }}</option>
                        @endforeach
                    @else
                        <option value="">No hay personal de soporte disponible</option>
                    @endif
                </select>
            </div>

            <button type="submit" class="btn btn-success" id="submit-btn">Enviar Solicitud</button>
            <a href="{{ route('dashboard.estudiante') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<!-- Scripts específicos para esta página -->
<script defer src="{{ asset('js/solicitud-kit.js') }}"></script>

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

<script>
// Esperar a que Bootstrap esté disponible
function waitForBootstrap(callback, maxAttempts = 10) {
    let attempts = 0;
    const checkBootstrap = () => {
        attempts++;
        if (typeof bootstrap !== 'undefined') {
            callback();
        } else if (attempts < maxAttempts) {
            setTimeout(checkBootstrap, 100);
        } else {
            console.error('Bootstrap no se pudo cargar después de varios intentos');
        }
    };
    checkBootstrap();
}

// Función para cargar componentes
async function loadComponentes(kitId) {
    const componentesList = document.getElementById('componentes-list');
    if (!componentesList || !kitId) return;
    
    try {
        console.log('Cargando componentes para kit:', kitId);
        componentesList.innerHTML = '<div class="text-muted">Cargando componentes...</div>';
        
        const res = await fetch(`/kits/${kitId}/componentes`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!res.ok) throw new Error(`Error al obtener componentes (${res.status})`);
        
        const comps = await res.json();
        console.log('Componentes recibidos:', comps);
        
        if (!Array.isArray(comps) || comps.length === 0) {
            componentesList.innerHTML = '<div class="text-muted">No hay componentes disponibles para este kit.</div>';
            return;
        }

        componentesList.innerHTML = '';
        const container = document.createElement('div');
        container.className = 'list-group';

        comps.forEach(c => {
            const item = document.createElement('div');
            item.className = 'list-group-item mb-2';
            item.innerHTML = `
                <div class="d-flex align-items-start">
                    <div class="form-check me-3">
                        <input class="form-check-input componente-checkbox" type="checkbox" value="1" id="comp_chk_${c.id_componente}" data-id="${c.id_componente}">
                    </div>
                    <div class="flex-fill">
                        <div class="fw-bold">${c.nombre}</div>
                        <div class="small text-muted">Stock: ${c.stock} — Por defecto: ${c.cantidad_por_kit}</div>
                    </div>
                    <div style="width:140px;" class="text-end">
                        <label class="form-label small mb-1">Cantidad</label>
                        <input type="number" 
                            name="componentes[${c.id_componente}]" 
                            class="form-control componente-cantidad" 
                            value="0"
                            min="0" 
                            max="${c.stock}" 
                            disabled />
                    </div>
                </div>
            `;
            container.appendChild(item);
        });

        componentesList.appendChild(container);

        // Añadir listeners a los checkboxes
        container.querySelectorAll('.componente-checkbox').forEach(chk => {
            chk.addEventListener('change', function(e){
                const id = this.dataset.id;
                const input = container.querySelector(`input[name="componentes[${id}]"]`);
                if (input) {
                    input.disabled = !this.checked;
                    if (!this.checked) input.value = 0;
                    else if (input.value == 0) input.value = 1;
                }
            });
        });

        // Aplicar el estado actual del kit
        applyEstadoKit();
        
    } catch (err) {
        console.error('Error:', err);
        componentesList.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
    }
}

// Función para aplicar el estado del kit
function applyEstadoKit() {
    const estadoSelect = document.querySelector('select[name="estado_kit"]');
    const area = document.getElementById('componentes-area');
    const checkboxes = document.querySelectorAll('.componente-checkbox');
    const cantidades = document.querySelectorAll('.componente-cantidad');
    
    if (!estadoSelect || !area) return;
    
    const val = estadoSelect.value;
    console.log('Aplicando estado:', val);
    
    if (val === 'Solo componentes específicos') {
        area.style.display = 'block';
        checkboxes.forEach(c => { 
            c.disabled = false;
            c.checked = false;
        });
        cantidades.forEach(i => {
            i.disabled = true;
            i.value = 0;
        });
    } else if (val === 'Completo') {
        area.style.display = 'block';
        checkboxes.forEach(c => {
            c.disabled = true;
            c.checked = true;
        });
        cantidades.forEach(i => {
            i.disabled = true;
            i.value = 1;
        });
    } else {
        area.style.display = 'none';
    }
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que Bootstrap esté disponible antes de inicializar
    waitForBootstrap(() => {
        const form = document.getElementById('solicitud-kit-form');
        const kitSelect = document.querySelector('select[name="id_kit"]');
        const estadoSelect = document.querySelector('select[name="estado_kit"]');
        const componentesList = document.getElementById('componentes-list');

        // Configurar y mostrar toast
        const successToast = document.getElementById('successToast');
        if (successToast && window.location.search.includes('success=true')) {
            const toast = new bootstrap.Toast(successToast);
            toast.show();
        }

        // Validación del formulario
        if (form) {
            form.addEventListener('submit', function(e) {
                if (estadoSelect.value === 'Solo componentes específicos') {
                    const cantidades = document.querySelectorAll('.componente-cantidad');
                    let hasComponents = false;
                    
                    cantidades.forEach(input => {
                        if (parseInt(input.value) > 0) hasComponents = true;
                    });
                    
                    if (!hasComponents) {
                        e.preventDefault();
                        alert('Debe seleccionar al menos un componente y especificar una cantidad mayor a 0 cuando el estado es "Solo componentes específicos"');
                    }
                }
            });
        }

        // Inicializar lista de componentes
        if (componentesList) {
            componentesList.innerHTML = '<div class="text-muted">Seleccione un kit para ver los componentes disponibles.</div>';
        }

        // Evento de cambio de kit
        if (kitSelect) {
            kitSelect.addEventListener('change', (e) => {
                if (e.target.value) {
                    loadComponentes(e.target.value);
                } else {
                    componentesList.innerHTML = '<div class="text-muted">Seleccione un kit para ver los componentes disponibles.</div>';
                }
            });

            if (kitSelect.value) {
                loadComponentes(kitSelect.value);
            }
        }

        // Evento de cambio de estado
        if (estadoSelect) {
            estadoSelect.addEventListener('change', applyEstadoKit);
            applyEstadoKit();
        }
    });
});</script>
</script>

<!-- Gestión de integrantes ahora centralizada en layouts/app.blade.php
     (handler delegado). Si necesitas lógica específica para este formulario,
     añade data-attributes al botón y actualiza el handler global.
 -->

@if(!($in_dashboard ?? false))
            </div>
        </div>
    </div>

    @endsection
@endif
