@extends('base.index')

@section('title')
Leads
@endsection

@section('buttons')
    <a href="{{ route('leads.create') }}" class="btn btn-primary">
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
                            <th data-field="date" data-align="center">Data</th>
                            <th data-field="origin" data-align="center">Origem</th>
                            <th data-field="building" data-align="center">Empreendimento</th>
                            <th data-field="name" data-align="center">Nome</th>
                            <!--<th data-field="email" data-align="center">E-mail</th>-->
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center" data-events="operateEvents">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($leads as $lead)
                                { 
                                    'date': '{{ $lead->created_at->format("d/m/Y H:i:s") }}', 
                                    'origin': '{{ $lead->RelationOrigins->name }}', 
                                    'building': '{{ $lead->RelationBuildings->name }}', 
                                    'name': '{{ $lead->name }}',
                                    'email': '{{ $lead->email }}',
                                    'status': '@if( $lead->batches_id ) @if (Bus::findBatch($lead->batches_id)->failedJobs > 0 && Bus::findBatch($lead->batches_id)->pendingJobs > 0 ) <span class="badge border rounded-pill bg-danger-subtle border-danger-subtle text-danger-emphasis"> <i class="bi bi-x-octagon px-1"></i> Erro </span> @elseif (Bus::findBatch($lead->batches_id)->pendingJobs > 0 ) <span class="badge border rounded-pill bg-secondary-subtle border-secondary-subtle text-secondary-emphasis"> <i class="bi bi-gear-wide-connected px-1"></i> Executando </span> @elseif (Bus::findBatch($lead->batches_id)->pendingJobs === 0 ) <span class="badge border rounded-pill bg-success-subtle border-success-subtle text-success-emphasis"> <i class="bi bi-check2-circle px-1"></i> Finalizado </span> @endif @else <span class="badge border rounded-pill bg-info-subtle border-info-subtle text-info-emphasis"> <i class="bi bi-box-seam px-1"></i> Na fila </span> @endif',
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"> <a href="{{ route('leads.show', $lead->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a> @if($lead->batches_id && Bus::findBatch($lead->batches_id)->failedJobs > 0 && Bus::findBatch($lead->batches_id)->pendingJobs > 0) <a href="{{ route('leads.retry', $lead->id ) }}" class="btn btn-outline-danger px-2 py-1 retry" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tentar Novamente"><i class="bi bi-arrow-repeat"></i></a> @endif </div>'
                                },
                            @endforeach
                        ];

                        // Buttons click event
                        window.operateEvents = {
                            'click a.retry': function (e, value, row, index) {
                                if(!confirm('Tem certeza que deseja tentar novamente?')){
                                    return false;
                                }
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