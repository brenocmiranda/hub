@extends('base.index')

@section('title')
Pipelines
@endsection

@section('buttons')
    <a href="{{ route('leads.retryAll') }}" class="btn btn-danger retryAll">
        <i class="bi bi-arrow-repeat"></i>
        <span>Retry</span>
    </a>
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="table">
                    <thead>
                        <tr>
                            <th data-field="date" data-align="center">Data</th>
                            <th data-field="status" data-align="center">StatusCode</th>
                            <th data-field="integration" data-align="center">Integração</th>
                            <th data-field="origin" data-align="center">Origem do Lead</th>
                            <th data-field="lead" data-align="center">Nome do Lead</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($pipelines as $pipeline)
                                { 
                                    'date': '{{ $pipeline->created_at->format("d/m/Y H:i:s") }}', 
                                    'status': '{{ $pipeline->statusCode }}', 
                                    'integration': '{{ $pipeline->statusCode == 0 ? "Payload (" . $pipeline->RelationIntegrations->name . ")" : ($pipeline->statusCode == 1 ? "Disparo de e-mail" : ($pipeline->statusCode == 2 ? "Google Sheets" : ( $pipeline->RelationIntegrations ? $pipeline->RelationIntegrations->name : "" )))}}',
                                    'origin': '{{ $pipeline->RelationLeads->RelationOrigins->name }}',
                                    'lead': '{{ $pipeline->RelationLeads->name }}',
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"><a href="{{ route('leads.pipelines.show', $pipeline->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a></div>'
                                },
                            @endforeach
                        ];

                        // Buttons click event
                        window.operateEvents = {
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

@section('js')
    <script>
        $('.retryAll').on('click', function(){
            if(!confirm('Tem certeza que deseja tentar novamente todas que estão derão erro?')){
                return false;
            }
        });
    </script>
@endsection