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
                            <th data-field="name" data-align="center">Nome</th>
                            <th data-field="email" data-align="center" data-visible="false">E-mail</th>
                            <th data-field="companie" data-align="center">Empresa</th>
                            <th data-field="building" data-align="center">Empreendimento</th>
                            <th data-field="origin" data-align="center">Origem</th>
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
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