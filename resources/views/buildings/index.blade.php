@extends('base.index')

@section('title')
Empreendimentos
@endsection

@section('breadcrumb')
<div class="title">
    <h2>Empreendimentos</h2>
</div>
<div class="breadcrumb">
    <a href="{{ route('home') }}" class="text-decoration-none text-dark">
        <small>Home</small>
    </a>
    <i class="bi bi-chevron-double-right"></i>
    <a href="{{ route('view.buildings') }}" class="text-decoration-none text-dark">
        <small>Empreendimentos</small>
    </a>
</div>
@endsection

@section('content-page')
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                content-teste
            </div>
        </div>
    </div>
</section>
@endsection