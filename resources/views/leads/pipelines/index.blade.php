@extends('base.index')

@section('title')
Pipelines
@endsection

@section('buttons')
    @canany(['pipelines_resetAll', 'access_komuh']) 
        <a href="{{ route('leads.pipelines.retryAll') }}" class="btn btn-danger retryAll">
            <i class="bi bi-arrow-repeat"></i>
            <span>Retentar todos</span>
        </a>
    @endcanany
@endsection

@section('css')
    <link href="{{ asset('css/pipelines.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        @can('access_komuh')
            <div class="row row-gap-3 mb-4">
                <div class="col-lg-4 col-12">
                    <div class="card d-flex flex-row align-items-center rounded-1">
                        <div class="bg-success px-4 py-3 rounded-start-1">
                            <i class="bi bi-database-check text-white"></i>
                        </div>
                        <div class="px-4 w-100">
                            <h6 class="mb-0">Finalizadas</h6>
                            <h4 class="mb-0">{{ $requestSuccess }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="card d-flex flex-row align-items-center rounded-1">
                        <div class="bg-dark px-4 py-3 rounded-start-1">
                            <i class="bi bi-database text-white bi-lg"></i>
                        </div>
                        <div class="px-4 w-100">
                            <h6 class="mb-0">Executando</h6>
                            <h4 class="mb-0">{{ $requestPending }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="card d-flex flex-row align-items-center rounded-1">
                        <div class="bg-danger px-4 py-3 rounded-start-1">
                            <i class="bi bi-database-exclamation text-white"></i>
                        </div>
                        <div class="px-4 w-100">
                            <h6 class="mb-0">Com erros</h6>
                            <h4 class="mb-0">{{ $requestFail }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        
        <div class="row">
            <div class="col-12">
                <table id="table" data-ajax="ajaxRequest" data-side-pagination="server">
                    <thead>
                        <tr>
                            <th data-field="date" data-align="center">Data</th>
                            @can('access_komuh')
                                <th data-field="company" data-align="center">Empresa</th>
                            @endcan
                            <th data-field="status" data-align="center">StatusCode</th>
                            <th data-field="integration" data-align="center">Integração</th>
                            <th data-field="origin" data-align="center">Origem do Lead</th>
                            <th data-field="lead" data-align="center">Nome do Lead</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    // your custom ajax request here
                    function ajaxRequest(params) {
                        var url = '{{ route('leads.pipelines.data') }}'
                        $.get(url + '?' + $.param(params.data)).then(function (res) {
                            params.success(res)
                        })
                    }
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