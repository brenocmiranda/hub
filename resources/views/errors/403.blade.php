@extends('base.index')

@section('title')
Unauthorized
@endsection

@section('css')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column text-center">
                    <div class="img-unauthorized mb-4"></div>
                    <h1 class="mb-2">Ops!</h1>
                    <h3>Você não possui permissões para acessar esse conteúdo.</h3>
                    <small class="text-danger">Error: 401 - unauthorized</small>
                    <a href="{{ route('home') }}" class="btn btn-dark px-5 rounded-5 mt-4"> Volte para home</a>
                </div>
            </div>
        </div>
    </div>
@endsection