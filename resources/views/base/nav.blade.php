<nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-7 d-flex gap-4 align-items-center px-0">
                <div class="menu d-none d-md-block">
                    <a href="#" class="d-flex p-2 text-decoration-none text-dark border border-2 border-secondary bg-transparent rounded-3">
                        <i class="bi bi-list"></i>
                    </a>
                </div>
                @can('leads_show')
                    <div class="search">
                        <form action="{{ route('leads.search') }}" id="leadSearch" class="position-relative">
                            <div class="input-group flex-nowrap rounded-5 bg-transparent border border-2 border-secondary">
                                <input type="search" name="search" id="search" placeholder="Pesquisar por leads..." class="bg-transparent w-100 py-2 ps-4 pe-2 ">
                                <button type="submit" class="input-group-text rounded-circle border-0 bg-secondary text-white" id="search" style="margin: -1px;">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="result bg-light rounded-3 position-absolute z-3 mt-1 shadow" style="display: none;">
                                <div class="resultList p-3 pb-1"></div>
                                <a href="#" class="close d-block text-center text-white">
                                    <small><i class="bi bi-chevron-up"></i></small>
                                </a>
                            </div>
                        </form>
                    </div>
                @endcan
            </div>
            <div class="col-lg-6 col-5 d-flex justify-content-end gap-3 pe-0">
                <div class="settings d-flex align-items-center">
                    <!--
                    <div class="notify">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-bell"></i>
                        </a>
                    </div>
                -->
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
                                    {{ strtoupper(substr(Auth::user()->name,0,1))  }}
                                </div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Meu perfil</a></li>
                            @can('tokens_show')
                                <li><a class="dropdown-item" href="{{ route('tokens.index') }}">Meus tokens</a></li>
                            @endcan
                            <li><a class="dropdown-item" href="{{ route('activities') }}">Minhas atividades</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalExit">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>