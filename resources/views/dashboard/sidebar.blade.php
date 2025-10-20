{{-- Sidebar compartido para páginas del dashboard --}}
<nav class="col-md-2 d-none d-md-block bg-white sidebar shadow-sm border-end min-vh-100">
    <div class="d-flex flex-column h-100">
        <div class="p-3 border-bottom bg-primary text-white text-center rounded-top">
            <h5 class="mb-0 fw-bold">Menú</h5>
        </div>
        <ul class="nav flex-column py-3">
            @if(Auth::user() && Auth::user()->rol === 'Estudiante')
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'solicitudes.create') active bg-primary text-white @else text-dark @endif" href="{{ route('solicitudes.create') }}">
                        <i class="bi bi-plus-circle"></i>
                        Crear Solicitud (Servidor)
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'solicitudes.index') active bg-primary text-white @else text-dark @endif" href="{{ route('solicitudes.index') }}">
                        <i class="bi bi-clock-history"></i>
                        Historial de Solicitudes (Servidor)
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'solicitudes.kit.create') active bg-primary text-white @else text-dark @endif" href="{{ route('solicitudes.kit.create') }}">
                        <i class="bi bi-cpu"></i>
                        Nueva Solicitud de Kit Arduino
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'solicitudes.kit.index') active bg-primary text-white @else text-dark @endif" href="{{ route('solicitudes.kit.index') }}">
                        <i class="bi bi-archive"></i>
                        Historial de Solicitudes (Kits)
                    </a>
                </li>
            @else
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'reportes.index' || Route::currentRouteName() == 'dashboard.admin') active bg-primary text-white @else text-dark @endif" href="{{ route('reportes.index') }}">
                        <i class="bi bi-bar-chart"></i>
                        Informes y Métricas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'dashboard.autorizar') active bg-primary text-white @else text-dark @endif" href="{{ route('dashboard.autorizar') }}">
                        <i class="bi bi-check2-square"></i>
                        Autorizar Solicitudes
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'dashboard.reportes.kits') active bg-primary text-white @else text-dark @endif" href="{{ route('dashboard.reportes.kits') }}">
                        <i class="bi bi-box-seam"></i>
                        Reportes Kits
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center gap-2 rounded @if(Route::currentRouteName() == 'dashboard.autorizar.kits') active bg-primary text-white @else text-dark @endif" href="{{ route('dashboard.autorizar.kits') }}">
                        <i class="bi bi-clipboard-check"></i>
                        Autorizar Solicitudes (Kits)
                    </a>
                </li>
            @endif
            <li class="nav-item mt-auto">
                <a class="nav-link d-flex align-items-center gap-2 rounded text-danger bg-light" href="{{ route('logout') }}">
                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar sesión
                </a>
            </li>
        </ul>
    </div>
</nav>
