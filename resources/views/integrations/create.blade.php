@extends('base.index')

@section('title')
Nova integração
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p> Efetue a criação de um novo cadastro preenchendo as informações abaixo: </p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="formulario">

                    @if ($errors->any())
                        <div class="alert alert-danger col-12">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul> 
                        </div>
                    @endif

                    <form action="{{ route('integrations.store') }}" method="POST" class="row row-gap-3">
                        @csrf
 
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('slug') is-invalid @enderror slug" value="{{ old('slug') }}" disabled>
                                <input type="hidden" class="slug" name="slug" value="{{ old('slug') }}">
                                <label for="slug">Slug <abbr>*</abbr></label>
                            </div>
                        </div>
                        @can('access_komuh')
                            <div class="input-field col-lg-6 col-12">
                                <div class="form-floating">
                                    <select class="form-select @error('company') is-invalid @enderror" aria-label="Defina uma empresa" name="company" id="company" required>
                                        <option selected></option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company') != null && old('company') == $company->id ? 'selected' : "" }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="company">Empresas <abbr>*</abbr></label>
                                </div>
                            </div>
                        @endcan
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ old('active') != null && old('active') == true ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('active') != null && old('active') == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" required>
                                <label for="url">URL <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('type') is-invalid @enderror" aria-label="Defina um tipo" name="type" id="type" required>
                                    <option selected></option>
                                    <option value="GET" {{ old('type') == 'GET' ? '' : "selected" }}>GET</option>
                                    <option value="POST" {{ old('type') == 'POST' ? 'selected' : "" }}>POST</option>
                                </select>
                                <label for="type">Tipo <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('encoded') is-invalid @enderror" aria-label="Escolha se será enviado o body encoded" name="encoded" id="encoded" required>
                                    <option selected></option>
                                    <option value="1" {{ old('encoded') != null && old('encoded') == true ? '' : "selected" }}>Sim</option>
                                    <option value="0" {{ old('encoded') != null && old('encoded') == false ? 'selected' : "" }}>Não</option>
                                </select>
                                <label for="encoded">Encoded body? <abbr>*</abbr></label>
                            </div>
                        </div>
                        
                        <div class="divider-input">
                            <p>Autenticação</p>
                            <hr>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('user') is-invalid @enderror" id="user" name="user" value="{{ old('user') }}">
                                <label for="user">Usuário</label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}">
                                <label for="token">Password</label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('token') is-invalid @enderror" id="token" name="token" value="{{ old('token') }}">
                                <label for="token">Token</label>
                            </div>
                        </div>
                        <div class="divider-input">
                            <p>HTTP Header</p>
                            <hr>
                        </div>
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('header') is-invalid @enderror" id="header" name="header" style="height: 100px">{{ old('header') }}</textarea>
                                <label for="header">Header</label>
                                <small class="ms-1 text-secondary text-opacity-75">Obs.: Cada regra deve ser escrita em uma linha, entre aspas e com virgula ao final</small>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('integrations.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                            <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $('#name').on('keyup', function(){
        $('.slug').val(slugify($(this).val()));
    });
</script>
@endsection