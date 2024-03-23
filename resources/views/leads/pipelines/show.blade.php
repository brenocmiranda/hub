@extends('base.index')

@section('title')
Detalhes da pipeline <small>(Status: {{$pipeline->statusCode}})</small>
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p> As informações da pipiline estão disponíveis abaixo: </p>
            </div>
        </div>
        <div class="row mt-2 row-gap-3">
            <div class="divider-input col-12">
                <p>Dados do Lead</p>
                <hr>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $pipeline->RelationLeads->name }}" disabled>
                    <label for="name">Nome</label>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $pipeline->RelationLeads->email }}" disabled>
                    <label for="name">E-mail</label>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $pipeline->RelationLeads->phone }}" disabled>
                    <label for="name">Telefone</label>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $pipeline->RelationLeads->RelationBuildings->name }}" disabled>
                    <label for="name">Empreendimento</label>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $pipeline->RelationLeads->RelationOrigins->name }}" disabled>
                    <label for="name">Origem</label>
                </div>
            </div>
            @if($pipeline->RelationLeads->RelationFields->first())
                @foreach($pipeline->RelationLeads->RelationFields as $as)
                <div class="col-lg-6 col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" value="{{ $as->value }}" disabled>
                        <label for="name">{{$as->name}}</label>
                    </div>
                </div>
                @endforeach
            @endif
            <div class="divider-input col-12">
                <p>Header</p>
                <hr>
            </div>
            <div class="col-12">
                <code>
                    {{ $pipeline->RelationPipelinesLog->header }}
                </code>
            </div>
            <div class="divider-input col-12">
                <p class="text-break">Body</p>
                <hr>
            </div>
            <div class="col-12">
                @if($pipeline->statusCode == 0)
                    <code>
                        {{ $pipeline->RelationPipelinesLog->response }}
                    </code>
                @else
                    <code>
                        {{ json_decode($pipeline->RelationPipelinesLog->response) }}
                    </code>
                @endif
            </div>
        </div>
        <div class="row mt-3 justify-content-end">
            <div class="col-lg-2 col-12 d-flex justify-content-end">
                <a href="{{ route('leads.pipelines.index') }}" class="btn btn-primary"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
            </div>
        </div>
    </div>
@endsection