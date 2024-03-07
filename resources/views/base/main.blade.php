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

    <div class="modal fade p-4 py-md-5" tabindex="-1" role="dialog" id="modalDestroy" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-4 text-center">
                <h5>Tem certeza que deseja remover?</h5>
                <p class="mb-0">O registro será excluído permanentemente da nossa base. Caso precise recuperá-lo, deve acionar o administrador.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <a href="#" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end confirm"><strong>Sim, excluir!</strong></a>
                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">Não</button>
            </div>
            </div>
        </div>
    </div>
    
    @yield('modals')
</main>