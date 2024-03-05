@extends('base.index')

@section('title')
Novo empreendimento
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

                    <form action="{{ route('store.buildings') }}" method="POST" class="row row-gap-3">
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
                                <a href="#" onclick="addIntegration();"><i class="bi bi-plus"></i> Criar nova integração</a>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('index.buildings') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
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
                <span class="badge text-bg-secondary">$utm_source</span>
                <span class="badge text-bg-secondary">$utm_source_xrm</span>
                <span class="badge text-bg-secondary">$utm_medium</span>
                <span class="badge text-bg-secondary">$utm_campaing</span>
                <span class="badge text-bg-secondary">$utm_term</span>
                <span class="badge text-bg-secondary">$utm_content</span>
                <span class="badge text-bg-secondary">$nomeEmpreendimento</span>
                <span class="badge text-bg-secondary">$message</span>
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
    var field = 1;
    var integration = 1;

    function addField(element) {
        event.preventDefault();
        $(element).closest('.single-integration').find('.content-integration').append(`<div class="row"> <div class="input-field col-6"> <div class="form-floating"> <input type="text" class="form-control @error('integration[nameField][]') is-invalid @enderror" id="integrationFieldName-` + field + `" name="integration[nameField][]" value="{{ old('integration[nameField][]') }}" required> <label for="integrationFieldName-` + field + `">Nome do campo <abbr>*</abbr></label> </div> </div> <div class="input-field col-5"> <div class="form-floating"> <input type="text" class="form-control @error('integration[valueField][]') is-invalid @enderror" id="integrationFieldValor-` + field + `" name="integration[valueField][]" value="{{ old('integration[valueField][]') }}" required> <label for="integrationFieldValor-` + field + `">Valor <abbr>*</abbr></label> </div> </div> <div class="col-1 p-0"> <div class="d-flex align-items-center justify-content-start h-100"> <a href="#" class="btn btn-sm btn-outline-dark" onclick="removeField(this);"><i class="bi bi-dash"></i></a> </div> </div> </div>`);
        field++;
    }
    
    function removeField(element) {
        event.preventDefault();
        $(element).closest('.row').remove();
    }

    function addIntegration() {
        event.preventDefault();
        $('.integrations').find('.all-integration').append(`<div class="single-integration"> <div class="content-integration"> <div class="form-floating"> <select class="form-select @error('integration[nameIntegration]') is-invalid @enderror" aria-label="Defina uma integração" name="integration[nameIntegration]" id="integration-` + integration + `" required> <option selected></option> @foreach($integrations as $integration) <option value="{{ $integration->id }}" {{ old('integration') != null && old('integration') == $integration->id ? 'selected' : "" }}>{{ $integration->name }}</option> @endforeach </select> <label for="integration` + integration + `">Integrações <abbr>*</abbr></label> </div> </div> <div class="d-flex gap-2"> <a href="#" class="btn btn-sm btn-outline-dark" onclick="addField(this);"><i class="bi bi-plus"></i> Novo campo</a> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover Integração" onclick="removeIntegration(this);"><i class="bi bi-trash"></i></a></div> </div>`);
        integration++;

        // Enable toltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    function removeIntegration(element) {
        event.preventDefault();
        $(element).closest('.single-integration').remove();
    }
</script>
@endsection