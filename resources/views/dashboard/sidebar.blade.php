{{-- Sidebar compartido para páginas del dashboard --}}
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link @if(Route::currentRouteName() == 'reportes.index' || Route::currentRouteName() == 'dashboard.admin') active @endif" href="{{ route('reportes.index') }}">
                    Informes y Métricas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(Route::currentRouteName() == 'dashboard.autorizar') active @endif" href="{{ route('dashboard.autorizar') }}">
                    Autorizar Solicitudes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(Route::currentRouteName() == 'dashboard.reportes.kits') active @endif" href="{{ route('dashboard.reportes.kits') }}">Reportes Kits</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(Route::currentRouteName() == 'dashboard.autorizar.kits') active @endif" href="{{ route('dashboard.autorizar.kits') }}">Autorizar Solicitudes (Kits)</a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="{{ route('logout') }}">Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>
