@extends('base.index')

@section('title')
Importações de leads
@endsection

@section('css')
    <link href="{{ asset('css/imports.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <form action="{{ route('imports.leads.store') }}" class="row" method="POST">
            @csrf
            <div class="col-12"> 
                <div class="mb-3">
                    <label for="formFile" class="form-label">Selecione seu arquivo para importação</label>
                    <input class="form-control" type="file" id="formFile">
                </div>
                <div>
                    <p>Coloque o nome do cabeçalho de cada coluna que está localizada na planilha.</p>
                </div>
            </div>
        </form>
    </div>
@endsection
