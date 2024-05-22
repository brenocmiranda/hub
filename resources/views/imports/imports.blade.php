@extends('base.index')

@section('title')
Importações de leads
@endsection

@section('css')
    <link href="{{ asset('css/reports.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <form action="{{ route('reports.leads.generate') }}" class="row" method="POST">
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
                        @if($array)
                            @foreach($array as $index => $arr)
                                <optgroup label="{{ $index }}"> 
                                    @foreach($arr as $building)
                                        <option value="{{ $building->id }}" {{ old('building') != null && old('building') == $building->id ? 'selected' : "" }}>{{ $building->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                    <label for="building">Empreendimentos</label>
                </div>
                <div class="form-floating mb-2">
                    <select class="form-select @error('origem') is-invalid @enderror" aria-label="Defina uma origem" name="origem" id="origem">
                        <option selected></option>
                        @foreach($origins as $origin)
                            <option value="{{ $origin->id }}" {{ old('origem') != null && old('origem') == $origin->id ? 'selected' : "" }}>{{ $origin->name }}</option>
                        @endforeach
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
@endsection
