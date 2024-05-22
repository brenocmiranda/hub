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
                <p>Para efetuar a importação dos leads é necessário selecionar em qual coluna está cada informação.</p>
            </div>
            
            <div class="col-lg-6 col-12"> 
                <div class="list-group">
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="created_at" checked>
                        <span>
                            Data de cadastro
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="name" checked>
                        <span>
                            Nome
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="email" checked>
                        <span>
                            E-mail
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="phone" checked>
                        <span>
                            Telefone
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="origin" checked>
                        <span>
                            Origem
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="building" checked>
                        <span>
                            Empreendimento
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="companie">
                        <span>
                            Empresa
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="utm_source">
                        <span>
                            Utm_source
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="utm_medium">
                        <span>
                            Utm_medium
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="utm_campaign">
                        <span>
                            Utm_campaign
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="utm_content">
                        <span>
                            Utm_content
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="utm_term">
                        <span>
                            Utm_term
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="message">
                        <span>
                            Mensagem
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="partyNumber">
                        <span>
                            PartyNumber
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="srNumber">
                        <span>
                            SrNumber
                        </span>
                    </label>
                </div>
            </div>
        </form>
    </div>
@endsection
