@extends('base.index')

@section('title')
Editar empresa
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p> Efetue a alteração das informações preenchendo os campos abaixo: </p>
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
                    
                    <form action="{{ route('update.companies', $companie->id) }}" method="POST" class="row row-gap-3">
                        @csrf  
                        
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $companie->name ? $companie->name : old('name') }}" required>
                                <label for="name">Nome</label>
                            </div>
                        </div>
                        <div class="input-field col-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('slug') is-invalid @enderror slug" value="{{ $companie->slug ? $companie->slug : old('slug') }}" disabled>
                                <input type="hidden" class="slug" name="slug" value="{{ $companie->slug ? $companie->slug : old('slug') }}">
                                <label for="slug">Slug</label>
                            </div>
                        </div>
                        <div class="input-field col-6">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ (old('active') != null && old('active') == true) || $companie->active == true ? 'selected' : "" }}>Ativo</option>
                                    <option value="0" {{ (old('active') != null && old('active') == false) || $companie->active == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status</label>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('index.companies') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                            <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

