<main>
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">

                @if( Request::segment(2) != 'home' )
                    <div class="title">
                        <h2>@yield('title')</h2>
                    </div>
                    <div class="breadcrumb mb-0">

                        <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                            <small>Home</small>
                        </a>
                        
                        @switch( Request::segment(2) )
                            @case('dashboard')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.dashboard') }}" class="text-decoration-none text-dark">
                                    <small>Dashboards</small>
                                </a>
                                @break

                            @case('leads')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.leads') }}" class="text-decoration-none text-dark">
                                    <small>Leads</small>
                                </a>
                                @break

                            @case('companies')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.companies') }}" class="text-decoration-none text-dark">
                                    <small>Empresas</small>
                                </a>
                                @break

                            @case('buildings')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.buildings') }}" class="text-decoration-none text-dark">
                                    <small>Empreendimentos</small>
                                </a>
                                @break
                            
                            @case('integrations')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.integrations') }}" class="text-decoration-none text-dark">
                                    <small>Integrações</small>
                                </a>
                                @break

                            @case('users')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.users') }}" class="text-decoration-none text-dark">
                                    <small>Usuários</small>
                                </a>
                                @break
                        @endswitch

                        @switch( Request::segment(3) )
                            @case('create')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ './' . Request::segment(3) }}" class="text-decoration-none text-dark">
                                    <small>Cadastrar</small>
                                </a>
                                @break

                            @case('edit')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ '../' . Request::segment(3) . '/' . Request::segment(4) }}" class="text-decoration-none text-dark">
                                    <small>Editar</small>
                                </a>
                                @break

                            @case('roles')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.users.roles') }}" class="text-decoration-none text-dark">
                                    <small>Funções</small>
                                </a>
                                @break

                            @case('origins')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.leads.origins') }}" class="text-decoration-none text-dark">
                                    <small>Origem</small>
                                </a>
                                @break

                            @case('pipelines')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.leads.pipelines') }}" class="text-decoration-none text-dark">
                                    <small>Pipelines</small>
                                </a>
                                @break

                            @case('keys')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('index.buildings.keys') }}" class="text-decoration-none text-dark">
                                    <small>Chaves</small>
                                </a>
                                @break
                        @endswitch

                        @switch( Request::segment(4) )
                            @case('create')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ './' . Request::segment(4) }}" class="text-decoration-none text-dark">
                                    <small>Cadastrar</small>
                                </a>
                                @break

                            @case('edit')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ '../' . Request::segment(4) . '/' . Request::segment(4) }}" class="text-decoration-none text-dark">
                                    <small>Editar</small>
                                </a>
                                @break
                        @endswitch
                    </div>
                </div>
                @endif
                <div class="col-6 d-flex justify-content-end align-items-center gap-2">
                    @yield('buttons')
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
        </div>
    </header>

    <section>
        @yield('content-page')
    </section>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <small class="text-secondary">© Komuh {{date('Y')}} &bull; Version {{ env('APP_VERSION') }} </small>
                </div>
            </div>
        </div>
    </footer>

    @yield('modals')
</main>