@extends('base.index')

@section('title')
Relatório das integrações
@endsection

@section('css')
    <link href="{{ asset('css/reports.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <form action="{{ route('reports.integrations.generate') }}" class="row" method="POST">
            @csrf
            <div class="col-12">
                <p>Para gerar o relatório dos leads basta selecione os campos, o intervalo de data e qual o formato desejado.</p>
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
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="slug" checked>
                        <span>
                            Slug
                        </span>
                    </label>
                    <label class="list-group-item d-flex gap-2">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="type" checked>
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
@endsection
