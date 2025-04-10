<main>
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-sm-8 col-12">
                    <div class="title">
                        <h2>@yield('title')</h2>
                    </div>
                    <div class="breadcrumb mb-0">
                        <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                            <small>Home</small>
                        </a>
                        
                        @switch( Request::segment(2) )
                            @case('dashboards')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('general.dashboards.index') }}" class="text-decoration-none text-dark">
                                    <small>Dashboards</small>
                                </a>
                                @break

                            @case('leads')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('leads.index') }}" class="text-decoration-none text-dark">
                                    <small>Leads</small>
                                </a>
                                @break

                            @case('companies')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('companies.index') }}" class="text-decoration-none text-dark">
                                    <small>Empresas</small>
                                </a>
                                @break

                            @case('products')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">
                                    <small>Produtos</small>
                                </a>
                                @break
                            
                            @case('integrations')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('integrations.index') }}" class="text-decoration-none text-dark">
                                    <small>Integrações</small>
                                </a>
                                @break

                            @case('users')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('users.index') }}" class="text-decoration-none text-dark">
                                    <small>Usuários</small>
                                </a>
                                @break
                            
                            @case('imports')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('imports.index') }}" class="text-decoration-none text-dark">
                                    <small>Importações</small>
                                </a>
                                @break
                            
                            @case('reports')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('reports.index') }}" class="text-decoration-none text-dark">
                                    <small>Relatórios</small>
                                </a>
                                @break
                            
                            @case('profile')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('profile.edit') }}" class="text-decoration-none text-dark">
                                    <small>Meu perfil</small>
                                </a>
                                @break
                            
                            @case('tokens')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('tokens.index') }}" class="text-decoration-none text-dark">
                                    <small>Meus tokens</small>
                                </a>
                                @break
                            
                            @case('activities')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('activities') }}" class="text-decoration-none text-dark">
                                    <small>Minhas atividades</small>
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
                            
                            @case('leads')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ './' . Request::segment(3) }}" class="text-decoration-none text-dark">
                                    <small>Leads</small>
                                </a>
                                @break
                            
                            @case('products')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ './' . Request::segment(3) }}" class="text-decoration-none text-dark">
                                    <small>Produtos</small>
                                </a>
                                @break

                            @case('integrations')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ './' . Request::segment(3) }}" class="text-decoration-none text-dark">
                                    <small>Integrações</small>
                                </a>
                                @break
                        @endswitch

                        @switch( Request::segment(4) )
                            @case('edit')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ '../' . Request::segment(3) . '/' . Request::segment(4) }}" class="text-decoration-none text-dark">
                                    <small>Editar</small>
                                </a>
                                @break

                            @case('roles')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('users.roles.index') }}" class="text-decoration-none text-dark">
                                    <small>Funções</small>
                                </a>
                                @break

                            @case('origins')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('leads.origins.index') }}" class="text-decoration-none text-dark">
                                    <small>Origens</small>
                                </a>
                                @break

                            @case('pipelines')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('leads.pipelines.index') }}" class="text-decoration-none text-dark">
                                    <small>Pipelines</small>
                                </a>
                                @break

                            @case('keys')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ route('products.keys.index') }}" class="text-decoration-none text-dark">
                                    <small>Chaves</small>
                                </a>
                                @break
                        @endswitch

                        @switch( Request::segment(5) )
                            @case('create')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ './' . Request::segment(4) }}" class="text-decoration-none text-dark">
                                    <small>Cadastrar</small>
                                </a>
                                @break
                        @endswitch

                        @switch( Request::segment(6) )
                            @case('edit')
                                <i class="bi bi-chevron-double-right"></i>
                                <a href="{{ '../' . Request::segment(4) . '/' . Request::segment(4) }}" class="text-decoration-none text-dark">
                                    <small>Editar</small>
                                </a>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="col-lg-6 col-sm-4 col-12 mt-3 mt-sm-0 d-flex justify-content-sm-end align-items-center gap-2">
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
                    <small class="text-secondary">© KGroup {{ date('Y') }} &bull; Version {{ env('APP_VERSION') }} </small>
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
            <div class="modal-footer flex-wrap flex-lg-nowrap p-0">
                <form action="#" method="POST" class="w-100">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-lg btn-link fs-6 text-decoration-none col-lg-6 col-12 py-3 m-0 rounded-0 border-end fw-bold w-100 confirm">Sim, excluir!</button>
                </form>
                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-lg-6 col-12 py-3 m-0 rounded-0" data-bs-dismiss="modal">Não</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade p-4 py-md-5" tabindex="-1" role="dialog" id="modalExit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-4 text-center">
                <h5>Tem certeza que deseja sair?</h5>
                <p class="mb-0">Você será desconectado da plataforma.</p>
            </div>
            <div class="modal-footer flex-wrap flex-lg-nowrap p-0">
                <a href="{{ route('logout') }}" class="btn btn-lg btn-link fs-6 text-decoration-none col-lg-6 col-12 py-3 m-0 rounded-0 fw-bold confirm">Sim, sair!</a>
                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-lg-6 col-12 py-3 m-0 rounded-0" data-bs-dismiss="modal">Não</button>
            </div>
            </div>
        </div>
    </div>
    
    @yield('modals')
</main>