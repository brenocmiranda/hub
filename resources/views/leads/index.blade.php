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
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                            <tr>
                                <td> {{ $lead->created_at->format("d/m/Y H:i:s") }} </td>
                                <td> {{ $lead->RelationOrigins->name }} </td>
                                <td> {{ $lead->RelationBuildings->name }} </td>
                                <td> {{ $lead->name }} </td>
                                <!--<td> {{ $lead->email }} </td>-->
                                <td> 
                                    {{ $lead->batches_id }} 
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-2"> 
                                        <a href="{{ route('leads.show', $lead->id ) }}" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a> 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection