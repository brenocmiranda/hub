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
                <table id="table" data-ajax="ajaxRequest" data-side-pagination="server">
                    <thead>
                        <tr>
                            <th data-field="date" data-align="center">Data</th>
                            <th data-field="origin" data-align="center">Origem</th>
                            <th data-field="building" data-align="center">Empreendimento</th>
                            <th data-field="name" data-align="center">Nome</th>
                            <!--<th data-field="email" data-align="center">E-mail</th>-->
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                    <!--<tbody>
                        @foreach($leads as $lead)
                        <tr>
                            <td>{{ $lead->created_at->format("d/m/Y H:i:s") }}</td>
                            <td>{{ $lead->RelationOrigins->name }}</td>
                            <td>{{ $lead->RelationBuildings->name }}</td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>
                                @if( $lead->batches_id ) 
                                    @if(Bus::findBatch($lead->batches_id)->failedJobs > 0 && Bus::findBatch($lead->batches_id)->pendingJobs > 0 )
                                        <span class="badge border rounded-pill bg-danger-subtle border-danger-subtle text-danger-emphasis"> <i class="bi bi-x-octagon px-1"></i> Erro </span>
                                    @elseif(Bus::findBatch($lead->batches_id)->pendingJobs > 0 )
                                        <span class="badge border rounded-pill bg-secondary-subtle border-secondary-subtle text-secondary-emphasis"> <i class="bi bi-gear-wide-connected px-1"></i> Executando </span>
                                    @elseif (Bus::findBatch($lead->batches_id)->pendingJobs === 0 )
                                        <span class="badge border rounded-pill bg-success-subtle border-success-subtle text-success-emphasis"> <i class="bi bi-check2-circle px-1"></i> Finalizado </span>
                                    @endif  
                                @else 
                                    <span class="badge border rounded-pill bg-info-subtle border-info-subtle text-info-emphasis"> <i class="bi bi-box-seam px-1"></i> Na fila </span>
                                @endif 
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2"> 
                                    <a href="{{ route('leads.show', $lead->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a> 
                                    {!! ($lead->batches_id && Bus::findBatch($lead->batches_id)->failedJobs > 0 && Bus::findBatch($lead->batches_id)->pendingJobs > 0 ? '<a href="'. route('leads.retry', $lead->id ) .'" class="btn btn-outline-danger px-2 py-1 retry" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tentar Novamente"><i class="bi bi-arrow-repeat"></i></a>' : "") !!} 
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>-->
                </table>
                <script>
                // your custom ajax request here
                function ajaxRequest(params) {
                    var url = '{{ route('leads.data') }}'
                    $.get(url + '?' + $.param(params.data)).then(function (res) {
                        params.success(res)
                    })
                }
                </script>
            </div>
        </div>
    </div>
@endsection