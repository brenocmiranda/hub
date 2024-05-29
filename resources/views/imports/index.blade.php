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
                                    'data': '{{ $report->created_at->format("d/m/Y H:i:s") }}', 
                                    'name': '{{ $report->name }}', 
                                    'type': '{{ ($report->type === 'integrations' ? 'Integrações' : ($report->type === 'buildings' ? 'Empreendimentos' : 'Leads')) }}', 
                                    'status': '{!! ( $report->status === "Na fila" ? '<div class="badge text-bg-secondary">' . $report->status . '</div>' : ( $report->status === "Executando" ? '<div class="badge text-bg-primary">' . $report->status . '</div>' : ( $report->status === "Gerando" ? '<div class="badge text-bg-dark">' . $report->status . '</div>' : ( $report->status === "Pronto" ? '<div class="badge text-bg-success">' . $report->status . '</div>' : $report->status )))) !!}', 
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"> {!! $report->status === "Pronto" ? '<a href="' . asset("storage/exports/" . $report->name ) . '" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Download" download><i class="bi bi-download"></i></a>' : "" !!} <a href="{{ route('reports.destroy', $report->id ) }}" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>'
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
