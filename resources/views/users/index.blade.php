@extends('base.index')

@section('title')
Usuários
@endsection

@section('buttons')
    @can('users_create') 
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Novo</span>
        </a>
    @endcan
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="table" data-ajax="ajaxRequest" data-side-pagination="server">
                    <thead>
                        <tr>
                            <th data-field="name" data-align="center">Nome</th>
                            @can('access_komuh')
                                <th data-field="company" data-align="center">Empresa</th>
                            @endcan
                            <th data-field="role" data-align="center">Função</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    // your custom ajax request here
                    function ajaxRequest(params) {
                        var url = '{{ route('users.data') }}'
                        $.get(url + '?' + $.param(params.data)).then(function (res) {
                            params.success(res)
                        })
                    }
                </script>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade p-4 py-md-5" tabindex="-1" role="dialog" id="modalRecovery" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
        <div class="modal-body p-4 text-center">
            <h5>Enviar e-mail de redefinição de senha?</h5>
            <p class="mb-0">Será enviado um e-mail para o destinatário com as instruções de redefinição.</p>
        </div>
        <div class="modal-footer flex-nowrap p-0">
            <a href="#" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end confirm"><strong>Enviar</strong></a>
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">Não</button>
        </div>
        </div>
    </div>
    </div>
@endsection

