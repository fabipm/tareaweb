@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="min-width:350px; max-width:400px; width:100%;">
        <h2 class="mb-4 text-center">Iniciar Sesión</h2>
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        <div class="mt-3 text-center">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
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
