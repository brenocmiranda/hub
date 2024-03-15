<nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 d-flex gap-4 align-items-center">
                <div class="menu">
                    <a href="#"
                        class="d-flex p-2 text-decoration-none text-dark border border-dark bg-transparent rounded-3">
                        <i class="bi bi-list"></i>
                    </a>
                </div>
                <div class="search">
                    <div class="input-group flex-nowrap rounded-5 bg-transparent border border-dark">
                        <span class="input-group-text bg-transparent rounded-circle border-0" id="search">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" name="search" id="search" placeholder="Pesquisar por leads..."
                            class="py-2 bg-transparent">
                    </div>
                </div>
            </div>
            <div class="col-6 d-flex justify-content-end gap-3">
                <div class="settings d-flex align-items-center">
                    <div class="notify">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-bell"></i>
                        </a>
                    </div>
                </div>
                <div class="perfil">
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center justify-content-end text-black text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->src)
                                <img src="{{ Auth::user()->src ? asset('storage/profile/'. Auth::user()->src) : '' }}" alt="" width="40" height="40" class="rounded-circle me-1">
                            @else
                                <div class="perfil-img rounded-circle bg-secondary me-1 fw-bold text-white">
                                    {{ substr(Auth::user()->name,0,1)  }}
                                </div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Atividades</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>