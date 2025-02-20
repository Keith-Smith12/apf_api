<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">

        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="/home">
                <svg version="1.1" id="logo" class="navbar-brand-img brand-sm" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120" xml:space="preserve">
                    <g>
                        <polygon class="st0" points="78,105 15,105 24,87 87,87 	" />
                        <polygon class="st0" points="96,69 33,69 42,51 105,51 	" />
                        <polygon class="st0" points="78,33 15,33 24,15 87,15 	" />
                    </g>
                </svg>
            </a>
        </div>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a class="nav-link">
                    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">Dashboard</span><span class="sr-only">(current)</span>
                </a>
            </li>
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>UTILIZADORES</span>
            </p>
            <li class="nav-item dropdown">
                <li class="nav-item">
                    <a class="nav-link pl-3" href="{{ route('admin.usuarios.index') }}">
                         <i class="fe fe-file-users fe-16"></i>
                         <span class="ml-1 item-text">Utilizadores</span>
                    </a>
                </li>
            </li>

                        {{-- Logs --}}


            <p class="text-muted nav-heading mt-4 mb-1">
                <span>Titulos</span>
            </p>

            <p class="text-muted nav-heading mt-4 mb-1">
                <span>Configuração</span>
            </p>
        </ul>
    </nav>
</aside>
