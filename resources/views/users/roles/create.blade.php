@extends('base.index')

@section('title')
Nova função
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
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.roles.store') }}" method="POST" class="row row-gap-3">
                        @csrf
                        
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" min="1" max="100" required>
                                <label for="value">Value <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ old('active') != null && old('active') == true ? '' : 'selected' }}>Ativo</option>
                                    <option value="0" {{ old('active') != null && old('active') == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('users.roles.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                            <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection