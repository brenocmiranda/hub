@extends('base.index')

@section('title')
Detalhes do Lead
@endsection

@section('css')
    <link href="{{ asset('css/leads.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p> As informações do lead estão disponíveis abaixo: </p>
            </div>
        </div>
        <div class="row mt-2 row-gap-3">
            <div class="col-lg-4 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->name }}" disabled>
                    <label for="name">Nome</label>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->email }}" disabled>
                    <label for="name">E-mail</label>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->phone }}" disabled>
                    <label for="name">Telefone</label>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->RelationBuildings->name }}" disabled>
                    <label for="name">Empreendimento</label>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->RelationOrigins->name }}" disabled>
                    <label for="name">Origem</label>
                </div>
            </div>
            @if($lead->RelationFields->first())
                <div class="divider-input col-12">
                    <p>Campos personalizados</p>
                    <hr>
                </div>
                @foreach($lead->RelationFields as $as)
                <div class="col-lg-6 col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" value="{{ $as->value }}" disabled>
                        <label for="name">{{$as->name}}</label>
                    </div>
                </div>
                @endforeach
            @endif
            <div class="divider-input col-12">
                <p>Logs</p>
                <hr>
            </div>
            <div class="col-12">
                <ul class="timeline pe-4">
                    <li class="d-flex flex-wrap flex-column flex-md-row">
                        <h6 class="fw-bold w-75">Entrada do Lead</h6>
                        <span href="#" class="me-auto ms-auto-md w-25 text-left text-md-end mb-3 mb-md-0">{{ $lead->created_at->format("d/m/Y H:i:s") }}</span>
                        <p class="w-100">O lead teve entrada na plataforma através {{ $lead->api ? "da API" : "do Hub"}}.</p>
                    </li>
                    @if($lead->RelationPipelines->first())
                        @foreach($lead->RelationPipelines as $log)
                            @if($log->id === $log->RelationPipelinesLog->id)
                                @if($log->statusCode == 0)
                                    <li class="d-flex flex-wrap flex-column flex-md-row">
                                        <h6 class="fw-bold w-75">T: {{ $log->attempts }} - Dados enviados para o processo de <span class="text-decoration-underline">{{$log->RelationIntegrations->name}}</span>.</h6>
                                        <span href="#" class="me-auto ms-auto-md w-25 text-left text-md-end mb-3 mb-md-0">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                        @if($log->RelationPipelinesLog->first()->response)
                                            @foreach(json_decode($log->RelationPipelinesLog->response) as $index => $response)
                                                <small class="text-break d-block w-100">{{$index}} => {{$response}}</small>
                                            @endforeach
                                        @endif
                                    </li>
                                @elseif($log->statusCode == 200 || $log->statusCode == 201)
                                    <li class="d-flex flex-wrap flex-column flex-md-row">
                                        <h6 class="fw-bold w-75">T: {{ $log->attempts }} -  Execução do processo de <span class="text-decoration-underline">{{$log->RelationIntegrations->name}}</span>.</h6>
                                        <span href="#" class="me-auto ms-auto-md w-25 text-left text-md-end mb-3 mb-md-0">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                        <p class="w-100">A tentativa de envio do lead para integração resultou em <strong class="text-success">sucesso</strong>.</p>
                                    </li>
                                @elseif($log->statusCode == 400 || $log->statusCode == 500)
                                    <li class="d-flex flex-wrap flex-column flex-md-row">
                                        <h6 class="fw-bold w-75">T: {{ $log->attempts }} - Execução do processo de <span class="text-decoration-underline">{{$log->RelationIntegrations->name}}</span>.</h6>
                                        <span href="#" class="me-auto ms-auto-md w-25 text-left text-md-end mb-3 mb-md-0">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                        <p class="w-100">A tentativa de envio do lead para integração resultou em <strong class="text-danger">erro</strong>, em alguns instantes executaremos novamente.</p>
                                        <small class="text-break d-block w-100 ps-3 text-danger">{{ json_decode($log->RelationPipelinesLog->response) }}.</small>
                                    </li>
                                @else
                                    <li class="d-flex flex-wrap flex-column flex-md-row">
                                        <h6 class="fw-bold w-75">T: {{ $log->attempts }} - Execução do processo de <span class="text-decoration-underline">{{$log->RelationIntegrations->name}}</span>.</h6>
                                        <span href="#" class="me-auto ms-auto-md w-25 text-left text-md-end mb-3 mb-md-0">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                        <p class="w-100">A tentativa de envio do lead para integração resultou em {{ $log->statusCode }}.</p>
                                        <small class="text-break d-block w-100 ps-3">{{ $log->RelationPipelinesLog->response }}.</small>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <div class="row mt-3 justify-content-end">
            <div class="col-lg-2 col-12 d-flex justify-content-end">
                <a href="{{ route('leads.index') }}" class="btn btn-primary"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
            </div>
        </div>
    </div>
@endsection