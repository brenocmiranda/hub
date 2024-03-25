@extends('base.index')

@section('title')
Empresas
@endsection

@section('buttons')
    <a href="{{ route('companies.create') }}" class="btn btn-primary">
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
                            <th data-field="slug" data-align="center">Slug</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center" data-events="operateEvents">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($companies as $companie)
                                { 
                                    'name': '{{ $companie->name }}', 
                                    'slug': '{{ $companie->slug }}', 
                                    'status': ({{ $companie->active }} ? '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>' : '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>'), 
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"><a href="{{ route('companies.edit', $companie->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a><a href="{{ route('companies.destroy', $companie->id ) }}" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>'
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
