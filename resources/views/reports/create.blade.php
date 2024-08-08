@extends('base.index')

@section('title')
Novo relatório
@endsection

@section('css')
    <link href="{{ asset('css/reports.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @can('leads_show')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="leads-tab" data-bs-toggle="tab" data-bs-target="#leads-tab-pane" type="button" role="tab" aria-controls="leads-tab-pane" aria-selected="true">Leads</button>
                        </li>
                    @endcan
                    @can('buildings_show')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="buildings-tab" data-bs-toggle="tab" data-bs-target="#buildings-tab-pane" type="button" role="tab" aria-controls="buildings-tab-pane" aria-selected="false">Empreendimentos</button>
                        </li>
                    @endcan
                    @can('integrations_show')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="integrations-tab" data-bs-toggle="tab" data-bs-target="#integrations-tab-pane" type="button" role="tab" aria-controls="integrations-tab-pane" aria-selected="false">Integrações</button>
                        </li>
                    @endcan
                </ul>
                <div class="tab-content p-4 border border-secondary-subtle bg-white rounded" id="myTabContent">
                    @can('leads_show')
                        <div class="tab-pane fade show active" id="leads-tab-pane" role="tabpanel" aria-labelledby="leads-tab" tabindex="0">
                            <form action="{{ route('reports.store') }}" class="row" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="leads">
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
                                <div class="col-lg-6 col-12 mt-lg-0 mt-2"> 
                                    <div class="d-flex flex-wrap gap-2 mb-2 date">
                                        <div class="form-floating">
                                            <input type="date" class="form-control @error('dataInicial') is-invalid @enderror" name="dataInicial" id="data-inicial" required>
                                            <label for="data-inicial">Data Inicial <abbr>*</abbr></label>
                                        </div>
                                        <div class="form-floating">
                                            <input type="date" class="form-control @error('dataInicial') is-invalid @enderror" name="dataFinal" id="data-final" required>
                                            <label for="data-final">Data Final <abbr>*</abbr></label>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select @error('building') is-invalid @enderror" aria-label="Defina um empreendimento" name="building" id="building">
                                            <option selected></option>
                                            @if($buildings)
                                                @foreach($buildings as $index => $arr)
                                                    @can('access_komuh')
                                                        <optgroup label="{{ $index }}"> 
                                                    @endcan
                                                            @foreach($arr as $building)
                                                                <option value="{{ $building->id }}" {{ old('building') != null && old('building') == $building->id ? 'selected' : "" }}>{{ $building->name }}</option>
                                                            @endforeach
                                                    @can('access_komuh')
                                                        </optgroup>
                                                    @endcan
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="building">Empreendimentos</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select @error('building') is-invalid @enderror" aria-label="Defina um empreendimento" name="origem" id="origem">
                                            <option selected></option>
                                            @if($origins)
                                                @foreach($origins as $index => $arr)
                                                    @can('access_komuh')
                                                        <optgroup label="{{ $index }}"> 
                                                    @endcan
                                                        @foreach($arr as $origin)
                                                            <option value="{{ $origin->id }}" {{ old('origem') != null && old('origem') == $origin->id ? 'selected' : "" }}>{{ $origin->name }}</option>
                                                        @endforeach
                                                    @can('access_komuh')
                                                        </optgroup>
                                                    @endcan
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="origem">Origem</label>
                                    </div>
                                    <div class="form-floating">
                                        <select class="form-select @error('format') is-invalid @enderror" aria-label="Defina um status" name="format" id="format" required>
                                            <option selected></option>
                                            <option value="pdf">.pdf</option>
                                            <option value="xls">.xls</option>
                                            <option value="csv">.csv</option>
                                        </select>
                                        <label for="format">Formato <abbr>*</abbr></label>
                                    </div>
                                    <div class="submit-field d-flex justify-content-md-end justify-content-center align-items-center gap-3 mt-3">
                                        <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Gerar relatório" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endcan
                    @can('buildings_show')
                        <div class="tab-pane fade" id="buildings-tab-pane" role="tabpanel" aria-labelledby="buildings-tab" tabindex="0">
                            <form action="{{ route('reports.store') }}" class="row" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="buildings">
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
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="keys" checked>
                                            <span>
                                                Chaves
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="companie" checked>
                                            <span>
                                                Empresa
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="active" checked>
                                            <span>
                                                Status
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="destinatarios">
                                            <span>
                                                Destinatarios
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="integrationsBuild">
                                            <span>
                                                Integrações
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="sheets">
                                            <span>
                                                Sheets
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 mt-lg-0 mt-2">
                                    <div class="form-floating">
                                        <select class="form-select @error('format') is-invalid @enderror" aria-label="Defina um status" name="format" id="format" required>
                                            <option selected></option>
                                            <option value="pdf">.pdf</option>
                                            <option value="xls">.xls</option>
                                            <option value="csv">.csv</option>
                                        </select>
                                        <label for="format">Formato <abbr>*</abbr></label>
                                    </div>
                                    <div class="submit-field d-flex justify-content-md-end justify-content-center align-items-center gap-3 mt-3">
                                        <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Gerar relatório" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endcan
                    @can('integrations_show')
                        <div class="tab-pane fade" id="integrations-tab-pane" role="tabpanel" aria-labelledby="integrations-tab" tabindex="0">
                            <form action="{{ route('reports.store') }}" class="row" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="integrations">
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
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="slug" checked>
                                            <span>
                                                Slug
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="typeInt" checked>
                                            <span>
                                                Tipo
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="url" checked>
                                            <span>
                                                URL
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="encoded" checked>
                                            <span>
                                                Enconded
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="active" checked>
                                            <span>
                                                Status
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="user">
                                            <span>
                                                User
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="password">
                                            <span>
                                                Password
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="token">
                                            <span>
                                                Token
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="header">
                                            <span>
                                                Header
                                            </span>
                                        </label>
                                        <label class="list-group-item d-flex gap-2">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="companie">
                                            <span>
                                                Empresa
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 mt-lg-0 mt-2">
                                    <div class="form-floating">
                                        <select class="form-select @error('format') is-invalid @enderror" aria-label="Defina um status" name="format" id="format" required>
                                            <option selected></option>
                                            <option value="pdf">.pdf</option>
                                            <option value="xls">.xls</option>
                                            <option value="csv">.csv</option>
                                        </select>
                                        <label for="format">Formato <abbr>*</abbr></label>
                                    </div>
                                    <div class="submit-field d-flex justify-content-md-end justify-content-center align-items-center gap-3 mt-3">
                                        <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Gerar relatório" />
                                    </div>
                                </div>
                            </form> 
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

