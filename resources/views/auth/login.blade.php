@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Iniciar Sesión</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="codigo_usuario" class="form-label">Código de Usuario</label>
                            <input type="text" name="codigo_usuario" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" name="clave" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Si el usuario regresa a la página del login (por ejemplo con back), obtener token fresco
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Forzar recarga completa para evitar usar versiones en caché que contengan tokens viejos
            window.location.reload();
        }
    });
</script>
<script>
    // Intentar desregistrar cualquier Service Worker que pueda estar interceptando
    (function(){
        if (!('serviceWorker' in navigator)) return;

        try {
            // Obtener todas las registraciones y desregistrarlas
            navigator.serviceWorker.getRegistrations().then(function(regs){
                regs.forEach(function(r){
                    try { r.unregister(); } catch(e) { console.warn('sw unregister failed', e); }
                });
            }).catch(function(err){ console.warn('getRegistrations failed', err); });

            // Borrar caches de la Cache API para evitar respuestas cacheadas
            if (window.caches && caches.keys) {
                caches.keys().then(function(keys){
                    keys.forEach(function(key){
                        try { caches.delete(key); } catch(e) { console.warn('cache delete failed', e); }
                    });
                }).catch(function(err){ console.warn('cache.keys failed', err); });
            }
        } catch (err) {
            console.warn('Service worker cleanup error', err);
        }
    })();
</script>
@endsection
