@extends('base.index')

@section('title')
Pipelines
@endsection

@section('buttons')
    <a href="{{ route('leads.retryAll') }}" class="btn btn-danger retryAll">
        <i class="bi bi-arrow-repeat"></i>
        <span>Retry All</span>
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