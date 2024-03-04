<aside class="desktop">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark h-100">
        <a href="{{ route('home') }}" class="d-flex align-items-center justify-content-center mb-3 mb-md-0">
            <img src="{{ asset('images/logo-white.png') }}" width="170" class="img-desktop" />
            <img src="{{ asset('images/favicon-white.png') }}" width="40" class="img-mobile rounded-2" />
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('home') }}"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'home' ? 'active' : '' }}">
                    <i class="bi bi-house"></i>
                    <span class="module-name">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('index.dashboard') }}"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i>
                    <span class="module-name">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'leads' ? 'active' : '' }}"
                    data-bs-toggle="collapse" data-bs-target="#leads-collapse" aria-expanded="false">
                    <i class="bi bi-person-workspace"></i>
                    <span class="module-name">Leads</span>
                </a>
                <div class="collapse {{ Request::segment(2) == 'leads' ? 'show' : '' }}" id="leads-collapse">
                    <ul class="btn-toggle-nav fw-normal small list-unstyled ps-5 ms-2">
                        <li>
                            <a href="{{ route('index.leads') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'leads' && Request::segment(3) == '' ? 'text-secondary' : '
                                text-white' }}">Ver
                                todas</a>
                        </li>
                        <li>
                            <a href="{{ route('create.leads') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'leads' && Request::segment(3) == 'create' ? 'text-secondary' : '
                                text-white' }}">Cadastrar</a>
                        </li>
                        <li>
                            <a href="{{ route('index.leads.origins') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'leads' && Request::segment(3) == 'origins' ? 'text-secondary' : '
                                text-white' }}">Origens</a>
                        </li>
                        <li>
                            <a href="{{ route('create.leads') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'leads' && Request::segment(3) == 'jobs' ? 'text-secondary' : '
                                text-white' }}">Pipelines</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'companies' ? 'active' : '' }}"
                    data-bs-toggle="collapse" data-bs-target="#companies-collapse" aria-expanded="false">
                    <i class="bi bi-shop"></i>
                    <span class="module-name">Empresas</span>
                </a>
                <div class="collapse {{ Request::segment(2) == 'companies' ? 'show' : '' }}"
                    id="companies-collapse">
                    <ul class="btn-toggle-nav fw-normal small list-unstyled ps-5 ms-2">
                        <li>
                            <a href="{{ route('index.companies') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'companies' && Request::segment(3) == '' ? 'text-secondary' : '
                                text-white' }}">Ver
                                todas</a>
                        </li>
                        <li>
                            <a href="{{ route('create.companies') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'companies' && Request::segment(3) == 'create' ? 'text-secondary' : '
                                text-white' }}">Cadastrar</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'buildings' ? 'active' : '' }}"
                    data-bs-toggle="collapse" data-bs-target="#buildings-collapse" aria-expanded="false">
                    <i class="bi bi-buildings"></i>
                    <span class="module-name">Empreendimentos</span>
                </a>
                <div class="collapse {{ Request::segment(2) == 'buildings' ? 'show' : '' }}"
                    id="buildings-collapse">
                    <ul class="btn-toggle-nav fw-normal small list-unstyled ps-5 ms-2">
                        <li>
                            <a href="{{ route('index.buildings') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'buildings' && Request::segment(3) == '' ? 'text-secondary' : '
                                text-white' }}">Ver
                                todas</a>
                        </li>
                        <li>
                            <a href="{{ route('create.buildings') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'buildings' && Request::segment(3) == 'create' ? 'text-secondary' : '
                                text-white' }}">Cadastrar</a>
                        </li>
                        <li>
                            <a href="{{ route('create.buildings') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'buildings' && Request::segment(3) == 'keys' ? 'text-secondary' : '
                                text-white' }}">Chaves</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('index.integrations') }}"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'integrations' ? 'active' : '' }}">
                    <i class="bi bi-layers"></i>
                    <span class="module-name">Integrações</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#"
                    class="nav-link text-white d-flex gap-2 mb-2 {{ Request::segment(2) == 'users' ? 'active' : '' }}"
                    data-bs-toggle="collapse" data-bs-target="#users-collapse" aria-expanded="false">
                    <i class="bi bi-people"></i>
                    <span class="module-name">Usuários</span>
                </a>
                <div class="collapse {{ Request::segment(2) == 'users' ? 'show' : '' }}" id="users-collapse">
                    <ul class="btn-toggle-nav fw-normal small list-unstyled ps-5 ms-2">
                        <li>
                            <a href="{{ route('index.users') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'users' && Request::segment(3) == '' ? 'text-secondary' : '
                                text-white' }}">Ver
                                todas</a>
                        </li>
                        <li>
                            <a href="{{ route('create.users') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'users' && Request::segment(3) == 'create' ? 'text-secondary' : '
                                text-white' }}">Cadastrar</a>
                        </li>
                        <li>
                            <a href="{{ route('index.users.roles') }}"
                                class="d-inline-flex text-decoration-none mb-2 {{ Request::segment(2) == 'users' && Request::segment(3) == 'roles' ? 'text-secondary' : '
                                text-white' }}">Funções</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <hr>
        <div class="d-flex gap-2 align-items-center">
            @if(Auth::user()->src)
                <img src="{{ Auth::user()->src }}" alt="" width="40" height="40" class="rounded-circle me-1">
            @else
                <div class="perfil-img rounded-circle bg-secondary fw-bold text-white me-1">
                    {{ substr(Auth::user()->name,0,1)  }}
                </div>
            @endif
            <div class="d-flex flex-column name">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->RelationCompanies->name }}</small>
            </div>
        </div>
    </div>
</aside>