// Función para cargar componentes
async function loadComponentes(kitId) {
    const componentesList = document.getElementById('componentes-list');
    
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

        componentesList.innerHTML = ''; // Limpiar contenido anterior
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
            chk.addEventListener('change', function() {
                const id = this.dataset.id;
                const input = container.querySelector(`input[name="componentes[${id}]"]`);
                if (input) {
                    input.disabled = !this.checked;
                    input.value = this.checked ? 1 : 0;
                }
            });
        });

        // Aplicar el estado actual del kit
        applyEstadoKit();
        
    } catch (err) {
        console.error('Error al cargar componentes:', err);
        componentesList.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
    }
}

// Función para aplicar el estado del kit
function applyEstadoKit() {
    const estadoSelect = document.querySelector('select[name="estado_kit"]');
    if (!estadoSelect) return;
    
    const val = estadoSelect.value;
    const area = document.getElementById('componentes-area');
    const checkboxes = document.querySelectorAll('.componente-checkbox');
    const cantidades = document.querySelectorAll('.componente-cantidad');
    
    if (val === 'Solo componentes específicos') {
        if (area) area.style.display = '';
        checkboxes.forEach(c => { 
            c.disabled = false;
            c.checked = false;
        });
        cantidades.forEach(i => {
            i.disabled = true;
            i.value = 0;
        });
    } else if (val === 'Completo') {
        if (area) area.style.display = '';
        checkboxes.forEach(c => {
            c.disabled = true;
            c.checked = true;
        });
        cantidades.forEach(i => {
            i.disabled = true;
            i.value = 1;
        });
    } else {
        if (area) area.style.display = 'none';
    }
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('solicitud-kit-form');
    const kitSelect = document.querySelector('select[name="id_kit"]');
    const estadoSelect = document.querySelector('select[name="estado_kit"]');
    const componentesList = document.getElementById('componentes-list');
    
    // Mostrar mensaje inicial
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
    }
    
    // Evento de cambio de estado
    if (estadoSelect) {
        estadoSelect.addEventListener('change', applyEstadoKit);
    }
    
    // Si ya hay un kit seleccionado al cargar
    if (kitSelect && kitSelect.value) {
        loadComponentes(kitSelect.value);
    }
    
    // Validación del formulario
    if (form) {
        form.addEventListener('submit', function(e) {
            const estadoKit = estadoSelect.value;
            if (estadoKit === 'Solo componentes específicos') {
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
});