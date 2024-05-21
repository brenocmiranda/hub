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

                    <form action="{{ route('buildings.store') }}" method="POST" class="row row-gap-3" enctype="multipart/form-data">
                        @csrf
 
                        <div class="input-field col-lg-8 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Nome <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-4 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ old('active') != null && old('active') == true ? '' : 'selected' }}>Ativo</option>
                                    <option value="0" {{ old('active') != null && old('active') == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        
                        <div class="accordion" id="accordionItems">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="true" aria-controls="flush-collapse1">
                                        Parceiros
                                    </button>
                                </h2>
                                <div id="flush-collapse1" class="accordion-collapse collapse show" data-bs-parent="#accordionItems">
                                    <div class="accordion-body">
                                        <div class="partners">
                                            <div class="all-partners"></div>
                                            <div class="text-center">
                                                <a href="#" onclick="addPartner()"><i class="bi bi-person-plus pe-1"></i> Cadastrar novo parceiro</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse2" aria-expanded="false" aria-controls="flush-collapse2">
                                        Destinatários
                                    </button>
                                </h2>
                                <div id="flush-collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                                    <div class="accordion-body">
                                        <div class="emails">
                                            <div class="all-emails"></div>
                                            <div class="text-center">
                                                <a href="#" onclick="addEmail()"><i class="bi bi-envelope-plus pe-1"></i> Cadastrar novo destinatário</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        Google Sheets
                                    </button>
                                </h2>
                                <div id="flush-collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                                    <div class="accordion-body">
                                        <div class="sheets">
                                            <div class="all-sheets"></div>
                                            <div class="text-center">
                                                <a href="#" onclick="addSheet()"><i class="bi bi-cloud-plus pe-1"></i> Cadastrar novo sheets</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                        Integrações
                                    </button>
                                </h2>
                                <div id="flush-collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                                    <div class="accordion-body">
                                        <div class="integrations">
                                            <div class="all-integration"></div>
                                            <div class="text-center">
                                                <a href="#" onclick="addIntegration()" class="d-block mb-1"><i class="bi bi-terminal-plus pe-1"></i> Cadastrar nova integração</a>
                                                <a href="#" class="" data-bs-toggle="modal" data-bs-target="#modalInfo"><i class="bi bi-info-circle pe-1"></i> Mais informações sobre o preenchimento dos campos</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                <span class="badge text-bg-secondary">$utm_xrm</span>
                <span class="badge text-bg-secondary">$utm_source</span>
                <span class="badge text-bg-secondary">$utm_medium</span>
                <span class="badge text-bg-secondary">$utm_campaign</span>
                <span class="badge text-bg-secondary">$utm_content</span>
                <span class="badge text-bg-secondary">$utm_term</span>
                <span class="badge text-bg-secondary">$nomeEmpreendimento</span>
                <span class="badge text-bg-secondary">$message</span>
                <span class="badge text-bg-secondary">$PartyNumber</span>
                <span class="badge text-bg-secondary">$SrNumber</span>
            </div>
            <p class="mb-0">Segue o exemplo de como utilizar uma das variáveis:</p>
            <small class="fw-bold"> $nomeCompleto </small>
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
    var count = 0;
    var integration = 0;

    // Partners
    function addPartner() {
        event.preventDefault();
        $('.partners').find('.all-partners').append(`<div class="single-partner"> <div class="content-partner"> <div class="row row-gap-2"> <div class="input-field col-lg-5 col-12"> <div class="form-floating"> <select class="form-select" aria-label="Defina uma empresa" name="partner[]" id="companie-` + count + `" required> <option selected></option> @foreach($companies as $companie) <option value="{{ $companie->id }}">{{ $companie->name }}</option> @endforeach </select> <label for="companie-` + count + `">Empresas <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-4 col-12"> <div class="form-floating"> <select class="form-select" aria-label="Defina a quantidade de leads" name="leads[]" id="leads-` + count + `" required> <option value="99" selected>-</option> <option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> <option value="9">9</option> <option value="10">10</option> </select> <label for="leads-` + count + `">Quantidade de leads <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-3 col-12 d-flex align-items-center gap-3"> <div class="form-floating w-100"> <select class="form-select principal" aria-label="Defina o dono" name="main[]" id="main-` + count + `" required> <option value="1">Sim</option> <option value="0" selected>Não</option> </select> <label for="main-` + count + `">Principal <abbr>*</abbr></label> </div> </div> <div class="col-12 d-flex align-items-end justify-content-end"> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removePartner(this);"><i class="bi bi-trash"></i> Excluir parceiro</a> </div> </div> </div> </div>`);
        count++;

        // Deixando apenas um dono
        $('.principal').on('change', function(event){
            event.preventDefault();
            let id = $(this).attr('id');
            $('.principal').each(function(index, element){
                if( id != $(element).attr('id') ){
                    $(element).val(0);
                }
            });
        });
    }
    function removePartner(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover esse destinatário?')){
            $(element).closest('.single-partner').remove();
        }
    }

    // Emails
    function addEmail() {
        event.preventDefault();
        $('.emails').find('.all-emails').append(`<div class="single-email"> <div class="content-email"> <div class="form-floating"> <input type="email" class="form-control" id="email-` + count + `" name="email[]" required> <label for="email-` + count + `">E-mail <abbr>*</abbr></label> <a href="#" class="btn btn-sm btn-outline-danger rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover campo" onclick="removeEmail(this);"><i class="bi bi-dash"></i></a> </div> </div> </div>`);
        count++;

        // Enable toltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    function removeEmail(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover esse destinatário?')){
            $(element).closest('.single-email').remove();
        }
    }

    // Google Sheets
    function addSheet() {
        event.preventDefault();
        $('.sheets').find('.all-sheets').append(`<div class="single-sheet"> <div class="content-sheet"> <div class="row row-gap-2"> <div class="col-lg-6 col-12"> <div class="form-floating"> <input type="text" class="form-control" id="spreadsheetID-` + count + `" name="spreadsheetID[]" required> <label for="spreadsheetID-` + count + `">ID do Sheet <abbr>*</abbr></label> </div> </div> <div class="col-lg-6 col-12"> <div class="form-floating"> <input type="text" class="form-control" id="sheet-` + count + `" name="sheet[]" required> <label for="sheet-` + count + `">Aba da planilha <abbr>*</abbr></label> </div> </div> <div class="col-12"> <div class="form-floating"> <input type="file" class="form-control" id="file-` + count + `" name="file[]" required> <label for="file-` + count + `">File de autenticação (JSON) <abbr>*</abbr></label> </div> </div> <div class="col-12 d-flex align-items-end justify-content-end"> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeSheet(this);"><i class="bi bi-trash"></i> Excluir sheets</a> </div> </div> </div> </div>`);
        count++;
    }
    function removeSheet(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover esse sheets?')){
            $(element).closest('.single-sheet').remove();
        }
    }

    // Integrations
    function addIntegration() {
        event.preventDefault();
        $('.integrations').find('.all-integration').append(`<div class="single-integration"> <div class="content-integration"> <div class="form-floating"> <select class="form-select" aria-label="Defina uma integração" name="array[` + integration + `][nameIntegration]" id="integration-` + integration + `" required> <option selected></option> @foreach($integrations as $integration) <option value="{{ $integration->id }}">{{ $integration->name }}</option> @endforeach </select> <label for="integration-` + integration + `">Integrações <abbr>*</abbr></label> </div> </div> <div class="d-flex gap-2"> <a href="#" class="btn btn-sm btn-outline-dark" onclick="addField(this, ` + integration + `);"><i class="bi bi-plus"></i> Novo campo</a> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeIntegration(this);"><i class="bi bi-trash"></i> Excluir integração</a></div> </div>`);
        integration++;
    }
    function removeIntegration(element) {
        event.preventDefault();
        if(confirm('Tem certeza que deseja remover toda a integração?')){
            $(element).closest('.single-integration').remove();
        }
    }

    // Integrations (Fields)
    function addField(element, field) {
        event.preventDefault();
        $(element).closest('.single-integration').find('.content-integration').append(`<div class="row row-gap-2"> <div class="input-field col-lg-6 col-12"> <div class="form-floating"> <input type="text" class="form-control" id="integrationFieldName-` + count + `" name="array[` + field + `][nameField][]" required> <label for="integrationFieldName-` + count + `">Nome do campo <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-6 col-12 d-flex align-items-center gap-3"> <div class="form-floating w-100"> <input type="text" class="form-control" id="integrationFieldValor-` + count + `" name="array[` + field + `][valueField][]" required> <label for="integrationFieldValor-` + count + `">Valor <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-dark rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover campo" onclick="removeField(this);"><i class="bi bi-dash"></i></a> </div> </div>`);

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