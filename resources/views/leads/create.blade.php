@extends('base.index')

@section('title')
Novo lead
@endsection

@section('css')
    <link href="{{ asset('css/leads.css') }}" rel="stylesheet">
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

                    <form action="{{ route('leads.store') }}" method="POST" class="row row-gap-3">
                        @csrf
                        
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                <label for="email">E-mail <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="tel" class="form-control is-phone @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                <label for="phone">Telefone <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('building') is-invalid @enderror" aria-label="Defina um empreendimento" name="building" id="building" required>
                                    <option selected></option>
                                    @if($array)
                                        @foreach($array as $index => $arr)
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
                                <label for="building">Empreendimentos <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('origin') is-invalid @enderror" aria-label="Defina uma origem" name="origin" id="origin" required>
                                    <option selected></option>
                                    @foreach($origins as $origin)
                                        <option value="{{ $origin->id }}" {{ old('origin') != null && old('origin') == $origin->id ? 'selected' : "" }}>{{ $origin->name }}</option>
                                      
                                    @endforeach
                                </select>
                                <label for="origin">Origem <abbr>*</abbr></label>
                            </div>
                        </div>

                        <div class="accordion" id="accordionItems">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="true" aria-controls="flush-collapse1">
                                        Campos personalizados
                                    </button>
                                </h2>
                                <div id="flush-collapse1" class="accordion-collapse collapse show" data-bs-parent="#accordionItems">
                                    <div class="accordion-body">
                                        <div class="fields">
                                            <div class="all-fields"></div>
                                            <div>
                                                <a href="#" onclick="addField()"><i class="bi bi-plus"></i> Criar novo campo</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('leads.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
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
    // Adicionando e removendo fields
    var field = 0;

    function addField(element) {
        event.preventDefault();
        $('.fields').find('.all-fields').append(`<div class="content-fields"> <div class="row row-gap-2"> <div class="input-field col-lg-6 col-12"> <div class="form-floating"> <select class="form-select" name="array[nameField][]" id="integrationFieldName-` + field + `" required> <option selected>Selecione</option> <option value="utm_source">utm_source</option> <option value="utm_medium">utm_medium</option> <option value="utm_campaign">utm_campaign</option> <option value="utm_term">utm_term</option> <option value="utm_content">utm_content</option> <option value="gclid">gclid</option> <option value="fbclid">fbclid</option> <option value="plataforma">plataforma</option> <option value="pp">pp (Política de privacidade)</option> <option value="com">com (Receber comunicações)</option> <option value="message">Mensagem</option> <option value="url">URL</option> </select> <label for="integrationFieldName-` + field + `">Nome do campo <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-6 col-12 d-flex align-items-center gap-2"> <div class="form-floating w-100"> <input type="text" class="form-control" id="integrationFieldValor-` + field + `" name="array[valueField][]" required> <label for="integrationFieldValor-` + field + `">Valor <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-dark rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover campo" onclick="removeField(this);"><i class="bi bi-dash"></i></a> </div> </div>`);
        field++;

        // Enable toltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    function removeField(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover o campo?')){
            $(element).closest('.content-fields').remove();
        }
    }
</script>
@endsection