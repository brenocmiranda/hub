@extends('base.index')

@section('title')
Tokens
@endsection

@section('buttons')
    <a href="{{ route('users.tokens.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Novo</span>
    </a>
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                @if(session('token'))
                    <div class="alert alert-success">
                        <p class="mb-0 fw-bold">Seu token: {{session('token')}}</p>
                        <small>Esse token não pode ser recuperado. Caso seja perdido, será necessário a criação de um novo.</small>
                    </div>
                @endif

                <table id="table">
                    <thead>
                        <tr>
                            <th data-field="name" data-align="center">Nome</th>
                            <th data-field="operations" data-align="center">Operações</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(function () {
                        var data = [
                            @foreach($tokens as $token)
                                { 
                                    'name': '{{ $token->name }}', 
                                    'operations': '<div class="d-flex justify-content-center align-items-center gap-2"><a href="{{ route('users.tokens.destroy', $token->id ) }}" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>'
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