@extends('base.index')

@section('title')
Importações
@endsection

@section('buttons')
    <a href="{{ route('imports.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Nova</span>
    </a>
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="table">
                    <thead>
                        <tr>
                            <th data-field="data" data-align="center">Data</th>
                            <th data-field="name" data-align="center">Nome</th>
                            <th data-field="type" data-align="center">Tipo</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($imports as $import)
                                { 
                                    'data': '{{ $import->created_at->format("d/m/Y H:i:s") }}', 
                                    'name': '{{ $import->name }}', 
                                    'type': '{{ ($import->type === 'integrations' ? 'Integrações' : ($import->type === 'buildings' ? 'Empreendimentos' : 'Leads')) }}', 
                                    'status': '{!! ( $import->status === "Na fila" ? '<div class="badge text-bg-secondary">' . $import->status . '</div>' : ( $import->status === "Executando" ? '<div class="badge text-bg-primary">' . $import->status . '</div>' : ( $import->status === "Importando" ? '<div class="badge text-bg-dark">' . $import->status . '</div>' : ( $import->status === "Pronto" ? '<div class="badge text-bg-success">' . $import->status . '</div>' : $import->status )))) !!}', 
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"> {!! $import->status === "Pronto" ? '<a href="'. route('leads.index') .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar leads"><i class="bi bi-eye"></i></a>' : "" !!} <a href="{{ route('imports.destroy', $import->id ) }}" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>'
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
