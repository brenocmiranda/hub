@extends('base.index')

@section('title')
Editar usuário
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
                        <div class="alert alert-danger col-12">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="row row-gap-3">
                        @method('PUT')
                        @csrf
 
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ? old('name') : $user->name }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ? old('email') : $user->email }}" required>
                                <label for="name">E-mail <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ (old('active') != null && old('active') == true) || $user->active == 1 ? 'selected' : "" }}>Ativo</option>
                                    <option value="0" {{ (old('active') != null && old('active') == false) || $user->active == 0 ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('companies') is-invalid @enderror" aria-label="Defina uma empresa" name="companies" id="companies" required>
                                    <option selected></option>
                                    @foreach($companies as $companie)
                                        <option value="{{ $companie->id }}" {{ (old('companies') != null && old('companies') == $companie->id) || $companie->id == $user->companie_id ? 'selected' : "" }}>{{ $companie->name }}</option>
                                    @endforeach
                                </select>
                                <label for="companies">Empresas <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('roles') is-invalid @enderror" aria-label="Defina uma função" name="roles" id="roles" required>
                                    <option selected></option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ (old('roles') != null && old('roles') == $role->id) || $role->id == $user->user_role_id ? 'selected' : "" }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <label for="roles">Função <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('users.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                            <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

