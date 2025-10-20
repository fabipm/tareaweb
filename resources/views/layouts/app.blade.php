<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Préstamo de Servidores</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar full height and sticky */
        .sidebar {
            height: calc(100vh - 56px); /* navbar height */
            position: sticky;
            top: 56px;
            padding-top: 1rem;
            border-right: 1px solid #e9ecef;
        }

        main {
            padding-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Encabezado eliminado -->
    <main>
        <div id="main-content">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('js/solicitud-kit.js') }}"></script>
    <!-- Scripts: colocados fuera de <main> para no interferir con el render de contenido -->
    <script>
        // Envío AJAX para el formulario de solicitud con protección contra envíos dobles
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('#solicitud-form');
            if (!form) return;
            e.preventDefault();

            // Guard para prevenir múltiples envíos
            if (form.dataset.submitting === '1') return;
            form.dataset.submitting = '1';

            // Deshabilitar botones de submit mientras se procesa
            const submits = Array.from(form.querySelectorAll('[type="submit"]'));
            submits.forEach(b => b.disabled = true);

            const url = form.action;
            const formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(async res => {
                const data = await res.json().catch(() => null);
                const modalEl = document.getElementById('globalModal');
                const modalTitle = document.getElementById('globalModalLabel');
                const modalBody = document.getElementById('globalModalBody');

                // Asegurar que no haya instancias previas ni backdrops colgando
                try {
                    const existing = bootstrap.Modal.getInstance(modalEl);
                    if (existing) {
                        existing.hide();
                        existing.dispose();
                    }
                } catch (err) {
                    // ignore
                }
                // Eliminar backdrops manualmente si quedan
                document.querySelectorAll('.modal-backdrop').forEach(n => n.remove());

                if (!res.ok) {
                    // Mostrar errores dentro del modal
                    let html = '<div class="text-danger">';
                    if (data && data.errors) {
                        html += '<ul>';
                        for (const k in data.errors) {
                            data.errors[k].forEach(msg => html += `<li>${msg}</li>`);
                        }
                        html += '</ul>';
                    } else if (data && data.message) {
                        html += `<p>${data.message}</p>`;
                    } else {
                        html += 'Error al enviar la solicitud. Intenta nuevamente.';
                    }
                    html += '</div>';
                    modalTitle.textContent = 'Error';
                    modalBody.innerHTML = html;
                    const myModal = new bootstrap.Modal(modalEl);
                    myModal.show();
                    // reactivar botones
                    submits.forEach(b => b.disabled = false);
                    form.dataset.submitting = '0';
                    return;
                }

                if (data && data.success) {
                    modalTitle.textContent = 'Solicitud enviada';
                    modalBody.innerHTML = `<div class="text-success">${data.message || 'Solicitud registrada correctamente.'}</div>`;
                    const myModal = new bootstrap.Modal(modalEl);
                    myModal.show();
                    // dejar el formulario inactivo para evitar reenvío accidental
                    // pero reactivar el botón para permitir cerrar el modal
                    submits.forEach(b => b.disabled = false);
                    form.dataset.submitting = '0';
                    return;
                }
                // En caso de respuesta inesperada
                modalTitle.textContent = 'Respuesta inesperada';
                modalBody.innerHTML = '<div class="text-warning">Respuesta del servidor no reconocida.</div>';
                const myModal = new bootstrap.Modal(modalEl);
                myModal.show();
                submits.forEach(b => b.disabled = false);
                form.dataset.submitting = '0';
            }).catch(err => {
                console.error(err);
                const modalEl = document.getElementById('globalModal');
                const modalTitle = document.getElementById('globalModalLabel');
                const modalBody = document.getElementById('globalModalBody');

                try {
                    const existing = bootstrap.Modal.getInstance(modalEl);
                    if (existing) { existing.hide(); existing.dispose(); }
                } catch (e) {}
                document.querySelectorAll('.modal-backdrop').forEach(n => n.remove());

                modalTitle.textContent = 'Error de red';
                modalBody.innerHTML = '<div class="text-danger">No se pudo enviar la solicitud por un error de red.</div>';
                const myModal = new bootstrap.Modal(modalEl);
                myModal.show();
                const submits = Array.from(form.querySelectorAll('[type="submit"]'));
                submits.forEach(b => b.disabled = false);
                form.dataset.submitting = '0';
            });
        });
    </script>

    <script>
        // Agregar/quitar integrantes (delegated)
        document.addEventListener('click', function(e) {
            // Botón genérico del formulario de solicitudes
            if (e.target && e.target.id === 'add-integrante') {
                const list = document.getElementById('integrantes-list');
                if (!list) return;
                const index = list.children.length + 1;
                const div = document.createElement('div');
                div.className = 'card p-3 mb-2';
                div.innerHTML = `
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" name="integrantes[${index}][codigo]" class="form-control" placeholder="Código">
                        </div>
                        <div class="col-md-5 mb-2">
                            <input type="text" name="integrantes[${index}][nombre]" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="integrantes[${index}][tipo]" class="form-select">
                                <option value="Estudiante">Estudiante</option>
                                <option value="Docente">Docente</option>
                            </select>
                        </div>
                        <div class="col-md-1 mb-2">
                            <button type="button" class="btn btn-danger btn-sm remove-integrante">Eliminar</button>
                        </div>
                    </div>`;
                list.appendChild(div);
            }

            // Botón específico del formulario de kits (estructura compacta)
            if (e.target && e.target.id === 'add-integrante-kit') {
                const list = document.getElementById('integrantes-list');
                if (!list) return;
                const index = list.children.length + 1;
                const wrapper = document.createElement('div');
                wrapper.className = 'row g-2 mb-2 integrante-row align-items-end';
                wrapper.innerHTML = `
                    <div class="col-md-4">
                        <label class="form-label small">Código del integrante</label>
                        <input type="text" name="integrantes[${index}][codigo_estudiante]" class="form-control" placeholder="Código" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Nombre del integrante</label>
                        <input type="text" name="integrantes[${index}][nombre_estudiante]" class="form-control" placeholder="Nombre" />
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm w-100 remove-integrante">Eliminar</button>
                    </div>
                `;
                list.appendChild(wrapper);
            }

            // Eliminación universal (soporta .integrante-row y .card)
            if (e.target && e.target.classList && e.target.classList.contains('remove-integrante')) {
                // Priorizar la fila interna (.integrante-row) para evitar eliminar
                // la tarjeta contenedora del formulario (que también tiene clase .card).
                const row = e.target.closest('.integrante-row');
                if (row) { row.remove(); }
                else {
                    const card = e.target.closest('.card');
                    if (card) card.remove();
                }
            }
        });
    </script>

    <script>
        // Envío AJAX para el formulario de solicitud (compatibilidad antigua si existiera)
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('#solicitud-form');
            if (!form) return;
            // Este handler sólo actúa como respaldo si no existe el handler moderno
            // No realiza el preventDefault para evitar bloquear comportamientos normales
        });
    </script>

    <!-- Modal global para mensajes -->
    <div class="modal fade" id="globalModal" tabindex="-1" aria-labelledby="globalModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="globalModalLabel">Mensaje</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body" id="globalModalBody">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
</body>
</html>

<script>
    // Maneja enlaces con clase .ajax-load para cargar contenido dentro de #main-content
    document.addEventListener('click', function(e) {
        try {
            const a = e.target.closest('a.ajax-load');
            if (!a) return;

            // Sólo responder a clic izquierdo sin modificadores
            if (e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) {
                console.log('ajax-load: clic con modificador o no-izquierdo, dejar comportamiento por defecto');
                return; // permitir abrir en nueva pestaña si el usuario usó Ctrl/Cmd, etc.
            }

            const url = a.href;
            if (!url) return;
            e.preventDefault();
            e.stopPropagation();
            console.log('ajax-load: interceptado', url);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.text();
                })
                .then(html => {
                    // Si el servidor devolvió una página completa, extraemos #main-content
                    let fragment = html;
                    if (/<!doctype html>|<html/i.test(html)) {
                        try {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const main = doc.getElementById('main-content');
                            if (main) fragment = main.innerHTML;
                        } catch (err) {
                            console.warn('ajax-load: no se pudo parsear HTML completo, usando contenido entero');
                        }
                    }
                    const container = document.getElementById('main-content');
                    if (container) container.innerHTML = fragment;
                    window.history.pushState({}, '', url);
                    console.log('ajax-load: contenido cargado en #main-content (len=', (fragment||'').length, ')');
                }).catch(err => {
                    console.error('ajax-load error:', err);
                    window.location.href = url;
                });
        } catch (err) {
            console.error('ajax-load exception', err);
        }
    });

    // Maneja back/forward del navegador
    window.addEventListener('popstate', function() {
        fetch(location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text()).then(html => {
                const container = document.getElementById('main-content');
                if (container) container.innerHTML = html;
            }).catch(err => console.error('popstate fetch error', err));
    });
</script>
<script>
    // Función global utilizada como fallback en onclick de enlaces si el delegado no se activa
    function loadAjax(e, url) {
        try {
            if (e && e.button !== undefined && e.button !== 0) return true; // permitir otros botones
            if (!url) return true;
            e.preventDefault();
            console.log('loadAjax: cargando', url);
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.text();
                })
                .then(html => {
                    // Si el servidor devolvió una página completa, extraemos el #main-content
                    let fragment = html;
                    if (/<!doctype html>|<html/i.test(html)) {
                        try {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const main = doc.getElementById('main-content');
                            if (main) fragment = main.innerHTML;
                        } catch (err) {
                            console.warn('loadAjax: no se pudo parsear HTML completo, usando contenido entero');
                        }
                    }
                    const container = document.getElementById('main-content');
                    if (container) container.innerHTML = fragment;
                    window.history.pushState({}, '', url);
                    console.log('loadAjax: contenido cargado en #main-content (long=', (fragment||'').length, ')');
                }).catch(err => {
                    console.error('loadAjax error', err);
                    // fallback: navegar normalmente
                    window.location.href = url;
                });
            return false;
        } catch (err) {
            console.error('loadAjax exception', err);
            return true;
        }
    }
</script>
