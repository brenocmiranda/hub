@extends('base.index')

@section('title')
Novo empreendimento
@endsection

@section('css')
    <link href="{{ asset('css/buildings.css') }}" rel="stylesheet">
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

                    <form action="{{ route('buildings.store') }}" method="POST" class="row row-gap-3">
                        @csrf
 
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-6">
                            <div class="form-floating">
                                <select class="form-select @error('companie') is-invalid @enderror" aria-label="Defina uma empresa" name="companie" id="companie" required>
                                    <option selected></option>
                                    @foreach($companies as $companie)
                                        <option value="{{ $companie->id }}" {{ old('companie') != null && old('companie') == $companie->id ? 'selected' : "" }}>{{ $companie->name }}</option>
                                    @endforeach
                                </select>
                                <label for="companie">Empresas <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-6">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ old('active') != null && old('active') == true ? 'selected' : "" }}>Ativo</option>
                                    <option value="0" {{ old('active') != null && old('active') == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="divider-input col-12">
                            <p>Integrações
                                <a href="#" class="btn btn-sm btn-secondary rounded-circle ms-1 py-0 px-1" data-bs-toggle="modal" data-bs-target="#modalInfo"><i class="bi bi-info"></i></a>
                            </p>
                            <hr>
                        </div>
                        <div class="integrations">
                            <div class="all-integration"></div>
                            <div>
                                <a href="#" onclick="addIntegration()"><i class="bi bi-plus"></i> Criar nova integração</a>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('buildings.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                            <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
<div class="modal fade p-4 py-md-5" tabindex="-1" role="dialog" aria-labelledby="modalInfo" id="modalInfo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content rounded-3 shadow">
        <div class="modal-header p-4 justify-content-center text-center">
            <h3 class="mb-0 fw-bold">Variáveis cadastradas</h3>
        </div>
      <div class="modal-body p-4 text-center">
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                <span class="badge text-bg-secondary">$nomeCompleto</span>
                <span class="badge text-bg-secondary">$nomeInicial</span>
                <span class="badge text-bg-secondary">$nomeFinal</span>
                <span class="badge text-bg-secondary">$telefoneCompleto</span>
                <span class="badge text-bg-secondary">$dda</span>
                <span class="badge text-bg-secondary">$ddd</span>
                <span class="badge text-bg-secondary">$number</span>
                <span class="badge text-bg-secondary">$email</span>
                <span class="badge text-bg-secondary">$pp</span>
                <span class="badge text-bg-secondary">$origin</span>
                <span class="badge text-bg-secondary">$numberContact</span>
                <span class="badge text-bg-secondary">$utm_source</span>
                <span class="badge text-bg-secondary">$utm_source_xrm</span>
                <span class="badge text-bg-secondary">$utm_medium</span>
                <span class="badge text-bg-secondary">$utm_campaing</span>
                <span class="badge text-bg-secondary">$utm_term</span>
                <span class="badge text-bg-secondary">$utm_content</span>
                <span class="badge text-bg-secondary">$nomeEmpreendimento</span>
                <span class="badge text-bg-secondary">$message</span>
                <span class="badge text-bg-secondary">$numberTicket</span>
            </div>
            <p class="mb-0">Sempre que utilizar uma das variáveis aplicar chaves antes e após.</p>
            <small class="fw-bold">Exemplo: @{{ $nomeCompleto }}</small>
      </div>
      <div class="modal-footer flex-nowrap p-0">
        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-12 py-3 m-0 rounded-0" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
    // Adicionando e removendo integrações e fields
    var field = 0;
    var integration = 0;

    function addIntegration() {
        event.preventDefault();
        $('.integrations').find('.all-integration').append(`<div class="single-integration"> <div class="content-integration"> <div class="form-floating"> <select class="form-select" aria-label="Defina uma integração" name="array[` + integration + `][nameIntegration]" id="integration-` + integration + `" required> <option selected></option> @foreach($integrations as $integration) <option value="{{ $integration->id }}">{{ $integration->name }}</option> @endforeach </select> <label for="integration-` + integration + `">Integrações <abbr>*</abbr></label> </div> </div> <div class="d-flex gap-2"> <a href="#" class="btn btn-sm btn-outline-dark" onclick="addField(this, ` + integration + `);"><i class="bi bi-plus"></i> Novo campo</a> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeIntegration(this);"><i class="bi bi-trash"></i> Excluir integração</a></div> </div>`);
        integration++;

        // Enable toltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    function removeIntegration(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover toda a integração?')){
            $(element).closest('.single-integration').remove();
        }
    }
    function addField(element, count) {
        event.preventDefault();
        $(element).closest('.single-integration').find('.content-integration').append(`<div class="row"> <div class="input-field col-6"> <div class="form-floating"> <input type="text" class="form-control" id="integrationFieldName-` + field + `" name="array[` + count + `][nameField][]" required> <label for="integrationFieldName-` + field + `">Nome do campo <abbr>*</abbr></label> </div> </div> <div class="input-field col-6 d-flex align-items-center gap-2"> <div class="form-floating w-100"> <input type="text" class="form-control" id="integrationFieldValor-` + field + `" name="array[` + count + `][valueField][]" required> <label for="integrationFieldValor-` + field + `">Valor <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-dark rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover campo" onclick="removeField(this);"><i class="bi bi-dash"></i></a> </div> </div>`);
        field++;

        // Enable toltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    function removeField(element) {
        event.preventDefault();
        $(element).closest('.row').remove();
    }
</script>
@endsection