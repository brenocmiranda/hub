@extends('base.index')

@section('title')
Usuários
@endsection

@section('buttons')
    <a href="{{ route('users.roles.index') }}" class="btn btn-dark">
        <i class="bi bi-person-fill-gear"></i>
        <span>Funções</span>
    </a>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Novo</span>
    </a>
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="table">
                    <thead>
                        <tr>
                            <th data-field="name" data-align="center">Nome</th>
                            <th data-field="empresa" data-align="center">Empresa</th>
                            <th data-field="function" data-align="center">Função</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center" data-events="operateEvents">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($users as $user)
                                { 
                                    'name': '{{ $user->name }}', 
                                    'empresa': '{{ $user->RelationCompanies->name }}',
                                    'function': '{{ $user->RelationRules->name }}', 
                                    'status': ({{ $user->active }} ? '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>' : '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>'), 
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"><a href="{{ route('users.edit', $user->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a> <a href="{{ route('users.recovery', $user->id ) }}" class="btn btn-outline-secondary px-2 py-1 reset" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Redefinir senha"><i class="bi bi-envelope-arrow-up"></i></i></a><a href="{{ route('users.destroy', $user->id ) }}" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>'
                                },
                            @endforeach
                        ];

                         // Buttons click event
                        window.operateEvents = {
                            'click a.destroy': function (e, value, row, index) {
                                e.preventDefault();
                                $('#modalDestroy').find('form').attr('action', $(value).find('a.destroy').attr('href'));
                                $('#modalDestroy').modal('show');
                            },
                            'click a.reset': function (e, value, row, index) {
                                e.preventDefault();
                                $('#modalReset').find('form').attr('action', $(value).find('a.confirm').attr('href'));
                                $('#modalReset').modal('show');
                            },
                            'mouseover a': function (e, value, row, index) {
                                $('[data-bs-toggle="tooltip"]').tooltip({
                                    trigger: 'hover',
                                    html: true
                                });
                            },
                        };

                        $table.bootstrapTable('refreshOptions', {
                            data: data
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection

@section('modals')
<div class="modal fade p-4 py-md-5" tabindex="-1" role="dialog" id="modalReset" aria-hidden="true">
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

