@extends('base.index')

@section('title')
Importações
@endsection

@section('buttons')
    @can('imports_create') 
        <a href="{{ route('imports.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nova</span>
        </a>
    @endcan
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table id="table" data-ajax="ajaxRequest" data-side-pagination="server">
                    <thead>
                        <tr>
                            <th data-field="data" data-align="center">Data</th>
                            <th data-field="name" data-align="center">Nome</th>
                            <th data-field="type" data-align="center">Tipo</th>
                            @can('access_komuh')
                                <th data-field="company" data-align="center">Empresa</th>
                            @endcan
                            <th data-field="status" data-align="center">Status</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    // your custom ajax request here
                    function ajaxRequest(params) {
                        var url = '{{ route('imports.data') }}'
                        $.get(url + '?' + $.param(params.data)).then(function (res) {
                            params.success(res)
                        })
                    }
                </script>
            </div>
        </div>
    </div>
@endsection
