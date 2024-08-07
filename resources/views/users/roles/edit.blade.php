@extends('base.index')

@section('title')
Editar função
@endsection

@section('css')
    <link href="{{ asset('css/users.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p>Efetue a alteração das informações preenchendo os campos abaixo:</p>
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
                    
                    <form action="{{ route('users.roles.update', $role->id) }}" method="POST" class="row row-gap-3">
                        @method('PUT')
                        @csrf  
                        
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name ? $role->name : old('name') }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('companie') is-invalid @enderror" aria-label="Defina uma empresa" name="companie" id="companie" required>
                                    <option selected></option>
                                    @foreach($companies as $companie)
                                        <option value="{{ $companie->id }}" {{ (old('companie') != null && old('companie') == $companie->id) || $companie->id == $role->companies_id ? 'selected' : "" }}>{{ $companie->name }}</option>
                                    @endforeach
                                </select>
                                <label for="companie">Empresas <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ (old('active') != null && old('active') == true) || $role->active == true ? 'selected' : "" }}>Ativo</option>
                                    <option value="0" {{ (old('active') != null && old('active') == false) || $role->active == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row row-gap-3">
                                <?php
                                    $array = [
                                        'dashboards' => 'Dashboards',
                                        'leads' => 'Leads',
                                        'origins' => 'Origens',
                                        'pipelines' => 'Pipelines',
                                        'companies' => 'Empresas',
                                        'buildings' => 'Empreendimentos',
                                        'key' => 'Chaves',
                                        'integrations' => 'Integrações',
                                        'reports' => 'Relatórios',
                                        'imports' => 'Importações',
                                        'users' => 'Usuários',
                                        'roles' => 'Funções',
                                        'tokens' => 'Tokens',
                                    ]
                                ?>
                                @foreach($array as $index => $item) 
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="accordion" id="accordionItems-{{$index}}">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $index }}" aria-expanded="false" aria-controls="flush-collapse-{{ $index }}">
                                                        {{ $item }}
                                                    </button>
                                                </h2>
                                                <div id="flush-collapse-{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#accordionItems-{{$index}}">
                                                    <div class="accordion-body">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_show" id="check_{{ $index }}_show" {{ stripos($role->roles, $index . '_show') !== false ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="check_{{ $index }}_show">
                                                                Visualizar
                                                            </label>
                                                        </div>
                                                        @if($index !== 'dashboards' && $index !== 'pipelines')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_create" id="check_{{ $index }}_create" {{ stripos($role->roles, $index . '_create' ) !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_create">
                                                                    Cadastrar
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if($index !== 'leads' && $index != 'dashboards' && $index !== 'pipelines' && $index !== 'reports' && $index !== 'imports' && $index !== 'tokens')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_update" id="check_{{ $index }}_update" {{ stripos($role->roles, $index . '_update') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_update">
                                                                    Editar
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if($index !== 'dashboards' && $index !== 'pipelines')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_destroy" id="check_{{ $index }}_destroy" {{ stripos($role->roles, $index . '_destroy') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_destroy">
                                                                    Deletar
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if($index === 'pipelines')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_resetAll" id="check_{{ $index }}_resetAll" {{ stripos($role->roles, $index . '_resetAll') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_resetAll">
                                                                    Reenviar todos
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if($index === 'users')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_reset" id="check_{{ $index }}_reset" {{ stripos($role->roles, $index . '_reset') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_reset">
                                                                    Redefinir
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if($index === 'leads')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_retry" id="check_{{ $index }}_retry" {{ stripos($role->roles, $index . '_retry') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_retry">
                                                                    Retentar
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_resend" id="check_{{ $index }}_resend" {{ stripos($role->roles, $index . '_resend') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_resend">
                                                                    Reenviar
                                                                </label>
                                                            </div>
                                                        @endif
                                                        @if($index === 'buildings')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="roles[]" type="checkbox" value="{{ $index }}_duplicate" id="check_{{ $index }}_duplicate" {{ stripos($role->roles, $index . '_duplicate') !== false ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="check_{{ $index }}_duplicate">
                                                                    Duplicar
                                                                </label>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="submit-field d-flex flex-wrap justify-content-md-between justify-content-center align-items-center gap-3">
                            <div class="d-flex flex-wrap justify-content-center flex-row align-items-center gap-3">
                                <a href="#" class="markall"><i class="bi bi-bookmark-check px-2"></i>Marcar todos</a>
                                <a href="#" class="unmarked"><i class="bi bi-bookmark-x px-2"></i>Desmarcar todos</a>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-3">
                                <a href="{{ route('users.roles.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                                <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $('.markall').on('click', function(){
        $('input[type=checkbox]').attr('checked', "");
    });

    $('.unmarked').on('click', function(){
        $('input[type=checkbox]').removeAttr('checked');
    });
</script>
@endsection

