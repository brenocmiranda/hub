@extends('base.index')

@section('title')
Home
@endsection

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row row-gap-3">
            <div class="col-lg-4 col-12">
                <div class="card d-flex flex-row align-items-center rounded-1">
                    <div class="bg-dark p-4 rounded-start-1">
                        <i class="bi bi-database text-white bi-lg"></i>
                    </div>
                    <div class="px-4 w-100">
                        <h6 class="mb-0">Jobs executando</h6>
                        <h4 class="mb-0">{{ $requestPending }}</h4>
                        <span>
                            <a href="{{ route('leads.pipelines.index') }}">Veja todas</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card d-flex flex-row align-items-center rounded-1">
                    <div class="bg-success p-4 rounded-start-1">
                        <i class="bi bi-database-check text-white"></i>
                    </div>
                    <div class="px-4 w-100">
                        <h6 class="mb-0">Jobs success</h6>
                        <h4 class="mb-0">{{ $requestSuccess }}</h4>
                        <span>
                            <a href="{{ route('leads.pipelines.index') }}">Veja todas</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card d-flex flex-row align-items-center rounded-1">
                    <div class="bg-danger p-4 rounded-start-1">
                        <i class="bi bi-database-exclamation text-white"></i>
                    </div>
                    <div class="px-4 w-100">
                        <h6 class="mb-0">Jobs error</h6>
                        <h4 class="mb-0">{{ $requestFail }}</h4>
                        <span>
                            <a href="{{ route('leads.pipelines.index') }}">Veja todas</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="img-home"></div>
                    <h2 class="text-secondary fw-bolder text-center">Seja bem-vindo, {{ explode(" ", Auth::user()->name)[0] }}!</h2>
                </div>
            </div>
        </div>
    </div>
@endsection