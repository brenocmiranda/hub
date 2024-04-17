@extends('base.index')

@section('title')
Integrações
@endsection


@section('buttons')
    <a href="{{ route('integrations.create') }}" class="btn btn-primary">
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
                            <th data-field="name" data-align="center" data-width="160">Nome</th>
                            <th data-field="type" data-align="center" data-width="100">Tipo</th>
                            <th data-field="url" data-align="left">URL</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($integrations as $integration)
                                { 
                                    'name': '{{ $integration->name }}', 
                                    'type': '{{ $integration->type }}',
                                    'url': '<small>{{ mb_strimwidth($integration->url, 0, 100, "...") }}</small>',
                                    'status': ({{ $integration->active }} ? '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>' : '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>'), 
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"><a href="{{ route('integrations.edit', $integration->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a><a href="{{ route('integrations.destroy', $integration->id ) }}" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>'
                                },
                            @endforeach
                        ];

                        $table.bootstrapTable('refreshOptions', {
                            data: data
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection

