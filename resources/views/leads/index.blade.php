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
                            <th data-field="email" data-align="center">E-mail</th>
                            <th data-field="operations" data-align="center">Operações</th>
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
                                    'operations': '<a href="{{ route('leads.show', $lead->id ) }}" class="btn btn-outline-secondary me-1 px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar"><i class="bi bi-eye"></i></a>'
                                },
                            @endforeach
                        ];

                        $table.bootstrapTable('refreshOptions', {
                            data: data
                        });

                        // Enable toltips
                        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
                    });
                </script>
            </div>
        </div>
    </div>
@endsection