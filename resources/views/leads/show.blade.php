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
            <div class="col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->name }}" disabled>
                    <label for="name">Nome</label>
                </div>
            </div>
            <div class="col-4">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $lead->email }}" disabled>
                    <label for="name">E-mail</label>
                </div>
            </div>
            <div class="col-4">
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
                    <li class="d-flex flex-wrap">
                        <h6 class="fw-bold w-75">Entrada do Lead</h6>
                        <span href="#" class="ms-auto w-25 text-end">{{ $lead->created_at->format("d/m/Y H:i:s") }}</span>
                        <p class="w-100">O lead teve entrada na plataforma através {{ $lead->api ? "da API" : "do Hub"}}.</p>
                    </li>
                    @if($lead->RelationPipelines->first())
                        @foreach($lead->RelationPipelines as $log)
                            @if($log->statusCode == 0)
                                <li class="d-flex flex-wrap">
                                    <h6 class="fw-bold w-75">Dados enviados para o processo de {{$log->RelationIntegrations->name}}.</h6>
                                    <span href="#" class="ms-auto w-25 text-end">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                    @if($log->RelationPipelinesLog->first()->response)
                                        @foreach(json_decode($log->RelationPipelinesLog->first()->response) as $index => $response)
                                            <small class="text-break d-block w-100">{{$index}} => {{$response}}</small> <br>
                                        @endforeach
                                    @endif
                                </li>
                            @elseif($log->statusCode == 200 || $log->statusCode == 201)
                                <li class="d-flex flex-wrap">
                                    <h6 class="fw-bold w-75">Execução do processo de {{$log->RelationIntegrations->name}}.</h6>
                                    <span href="#" class="ms-auto w-25 text-end">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                    <p class="w-100">A tentativa de envio do lead para integração resultou em <strong class="text-success">sucesso</strong>.</p>
                                </li>
                            @elseif($log->statusCode == 400)
                                <li class="d-flex flex-wrap">
                                    <h6 class="fw-bold w-75">Execução do processo de {{$log->RelationIntegrations->name}}.</h6>
                                    <span href="#" class="ms-auto w-25 text-end">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                    <p class="w-100">A tentativa de envio do lead para integração resultou em <strong class="text-danger">erro</strong>, em alguns instantes executaremos novamente.</p>
                                    <small class="text-break d-block w-100 ps-3 text-danger">{{ $log->RelationPipelinesLog->first()->response }}.</small>
                                </li>
                            @else
                                <li class="d-flex flex-wrap">
                                    <h6 class="fw-bold w-75">Execução do processo de {{$log->RelationIntegrations->name}}.</h6>
                                    <span href="#" class="ms-auto w-25 text-end">{{ $log->created_at->format("d/m/Y H:i:s") }}</span>
                                    <p class="w-100">A tentativa de envio do lead para integração resultou em {{ $log->statusCode }}.</p>
                                    <small class="text-break d-block w-100 ps-3">{{ $log->RelationPipelinesLog->first()->response }}.</small>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <div class="row mt-3 justify-content-end">
            <div class="col-2 d-flex justify-content-end">
                <a href="{{ route('leads.index') }}" class="btn btn-primary"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Adicionando e removendo fields
    var field = 0;

    function addField(element) {
        event.preventDefault();
        $('.fields').find('.all-fields').append(`<div class="content-fields"> <div class="row"> <div class="input-field col-lg-6 col-12"> <div class="form-floating"> <input type="text" class="form-control" id="integrationFieldName-` + field + `" name="array[nameField][]" required> <label for="integrationFieldName-` + field + `">Nome do campo <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-6 col-12 d-flex align-items-center gap-2"> <div class="form-floating w-100"> <input type="text" class="form-control" id="integrationFieldValor-` + field + `" name="array[valueField][]" required> <label for="integrationFieldValor-` + field + `">Valor <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-dark rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover campo" onclick="removeField(this);"><i class="bi bi-dash"></i></a> </div> </div> </div> `);
        field++;

        // Enable toltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    function removeField(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover o campo?')){
            $(element).closest('.content-fields').remove();
        }
    }
</script>
@endsection