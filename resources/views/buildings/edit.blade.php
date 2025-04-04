@extends('base.index')

@section('title')
Editar empreendimento
@endsection

@section('css')
    <link href="{{ asset('css/buildings.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
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
                    
                    <form action="{{ route('buildings.update', $building->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf  
                        
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-infos-tab" data-bs-toggle="tab" data-bs-target="#nav-infos" type="button" role="tab" aria-controls="nav-infos" aria-selected="true">Geral</button>

                            <button class="nav-link" id="nav-chaves-tab" data-bs-toggle="tab" data-bs-target="#nav-chaves" type="button" role="tab" aria-controls="nav-chaves" aria-selected="false">Chaves</button>

                            <button class="nav-link" id="nav-parceiros-tab" data-bs-toggle="tab" data-bs-target="#nav-parceiros" type="button" role="tab" aria-controls="nav-parceiros" aria-selected="false">Parceiros</button>

                            <button class="nav-link" id="nav-simulacoes-tab" data-bs-toggle="tab" data-bs-target="#nav-simulacoes" type="button" role="tab" aria-controls="nav-simulacoes" aria-selected="false">Simulações</button>
                            
                            <button class="nav-link" id="nav-email-tab" data-bs-toggle="tab" data-bs-target="#nav-email" type="button" role="tab" aria-controls="nav-email" aria-selected="false">E-mail</button>

                            <button class="nav-link" id="nav-googlesheets-tab" data-bs-toggle="tab" data-bs-target="#nav-googlesheets" type="button" role="tab" aria-controls="nav-googlesheets" aria-selected="false">Google Sheets</button>

                            <button class="nav-link" id="nav-integrations-tab" data-bs-toggle="tab" data-bs-target="#nav-integrations" type="button" role="tab" aria-controls="nav-integrations" aria-selected="false">Integrações</button>

                            <button class="nav-link" id="nav-meta-tab" data-bs-toggle="tab" data-bs-target="#nav-meta" type="button" role="tab" aria-controls="nav-meta" aria-selected="false">Meta Ads</button>
                        </div>

                        <div class="tab-content mb-4" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-infos" role="tabpanel" aria-labelledby="nav-infos-tab">
                                <div class="infos">
                                    <div class="row row-gap-3">
                                        <div class="input-field col-lg-12 col-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $building->name ? $building->name : old('name') }}" required>
                                                <label for="name">Nome <abbr>*</abbr></label>
                                            </div>
                                        </div>

                                        <div class="input-field col-lg-6 col-12">
                                            <div class="form-floating">
                                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                                    <option selected></option>
                                                    <option value="1" {{ (old('active') != null && old('active') == true) || $building->active == true ? 'selected' : "" }}>Ativo</option>
                                                    <option value="0" {{ (old('active') != null && old('active') == false) || $building->active == false ? 'selected' : "" }}>Desativado</option>
                                                </select>
                                                <label for="active">Status <abbr>*</abbr></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-chaves" role="tabpanel" aria-labelledby="nav-chaves-tab">
                                <div class="intro">
                                    <p>Nesta aba, bocê poderá configurar todas as chaves necessárias para o recebimento de leads por meio da nossa API.</p>
                                </div>
                                <div class="chaves">
                                    <div class="all-chaves"></div>
                                    <div>
                                        <a href="#" onclick="addChave()"><i class="bi bi-key pe-1"></i> Cadastrar nova chave</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-parceiros" role="tabpanel" aria-labelledby="nav-parceiros-tab">
                                <div class="intro">
                                    <p>Nesta aba, você pode configurar a distribuição dos leads e até mesmo cadastrar parceiros para recebê-los de forma automática.</p>
                                </div>
                                <div class="partners">
                                    <div class="all-partners">
                                        @if($building->RelationPartners->first())
                                            @foreach($building->RelationPartners as $index => $partners)
                                                <div class="single-partner">
                                                    <div class="content-partner">
                                                        <div class="row row-gap-2">
                                                            <div class="input-field col-lg-5 col-12">
                                                                <div class="form-floating">
                                                                    @if( $index == 0 )
                                                                        @cannot('access_komuh')
                                                                            <input type="hidden" name="partner[]" value="{{ Auth::user()->companies_id }}">
                                                                        @endcannot
                                                                    @endif
                                                                    <select class="form-select" aria-label="Defina uma empresa" name="partner[]" id="company-{{ $index }}" @if( $index == 0 ) @cannot('access_komuh') disabled @else required @endcan @endif>
                                                                        <option selected></option>
                                                                        @foreach($companies as $company) 
                                                                            <option value="{{ $company->id }}" {{ $partners->companies_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                                        @endforeach 
                                                                    </select>
                                                                    <label for="company-{{ $index }}">Empresas <abbr>*</abbr></label> 
                                                                </div>
                                                            </div>
                                                            <div class="input-field col-lg-3 col-12">
                                                                <div class="form-floating">
                                                                    <select class="form-select leads" aria-label="Defina a quantidade de leads" name="leads[]" id="leads-{{ $index }}" required>
                                                                        <option value="99" {{ $partners->leads == 99 ? 'selected' : '' }}>Todos</option>
                                                                        <option value="1" {{ $partners->leads == 1 ? 'selected' : '' }}>1</option>
                                                                        <option value="2" {{ $partners->leads == 2 ? 'selected' : '' }}>2</option>
                                                                        <option value="3" {{ $partners->leads == 3 ? 'selected' : '' }}>3</option>
                                                                        <option value="4" {{ $partners->leads == 4 ? 'selected' : '' }}>4</option>
                                                                        <option value="5" {{ $partners->leads == 5 ? 'selected' : '' }}>5</option>
                                                                        <option value="6" {{ $partners->leads == 6 ? 'selected' : '' }}>6</option>
                                                                        <option value="7" {{ $partners->leads == 7 ? 'selected' : '' }}>7</option>
                                                                        <option value="8" {{ $partners->leads == 8 ? 'selected' : '' }}>8</option>
                                                                        <option value="9" {{ $partners->leads == 9 ? 'selected' : '' }}>9</option>
                                                                        <option value="10" {{ $partners->leads == 10 ? 'selected' : '' }}>10</option>
                                                                    </select>
                                                                    <label for="leads-{{ $index }}">Quantidade de leads <abbr>*</abbr></label> 
                                                                </div>
                                                            </div>
                                                            <div class="input-field col-lg-4 col-12 d-flex align-items-center gap-3">
                                                                <div class="form-floating w-100">
                                                                    @if( $index == 0 )
                                                                        @cannot('access_komuh')
                                                                            <input type="hidden" name="main[]" value="1">
                                                                        @endcannot
                                                                    @else
                                                                        @cannot('access_komuh')
                                                                            <input type="hidden" name="main[]" value="0">
                                                                        @endcannot
                                                                    @endif
                                                                    <select class="form-select principal" aria-label="Defina o dono" name="main[]" id="main-{{ $index }}" @cannot('access_komuh') disabled @else required @endcan>
                                                                        <option value="1" {{ $partners->main == 1 ? 'selected' : '' }}>Sim</option>
                                                                        <option value="0" {{ $partners->main == 0 ? 'selected' : '' }}>Não</option>
                                                                    </select>
                                                                    <label for="main-{{ $index }}">Principal <abbr>*</abbr></label> 
                                                                </div>
                                                                @if( $index != 0 )
                                                                    <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removePartner(this);"><i class="bi bi-trash"></i></a> 
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div>
                                        <a href="#" onclick="addPartner()"><i class="bi bi-house-add pe-1"></i> Cadastrar novo parceiro</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-simulacoes" role="tabpanel" aria-labelledby="nav-simulacoes-tab">
                                <div class="intro">
                                    <p>Nesta aba, você tem a possibilidade de encaminhar os leads de teste para um caminho que não atrapalhe a sua fila comercial. </p>
                                </div>
                                <div class="simulacoes">
                                    <div class="row row-gap-3">
                                        <div class="input-field col-12">
                                             <div class="form-floating">
                                                <select class="form-select @error('buildings_id') is-invalid @enderror" aria-label="Defina um empreendimento de teste" name="buildings_id" id="buildings_id">
                                                    <option selected></option>
                                                    @if($buildingsAll) 
                                                        @foreach($buildingsAll as $index => $arr) 
                                                            @can('access_komuh') 
                                                                <optgroup label="{{ $index }}"> 
                                                            @endcan 

                                                            @foreach($arr as $buildingOnly) 
                                                                <option value="{{ $buildingOnly->id }}" {{ $building->buildings_id == $buildingOnly->id ? 'selected' : "" }}>{{ $buildingOnly->name }}</option> 
                                                            @endforeach 

                                                            @can('access_komuh') 
                                                                </optgroup> 
                                                            @endcan 
                                                        @endforeach 
                                                    @endif 
                                                </select>
                                                <label for="buildings_id">Empreendimento de teste</label>
                                            </div>
                                        </div>
                                        <small class="text-secondary">* Caso selecionado, todos os leads relacionados ao seu empreendimento que tiverem a palavra "teste" no <b>nome</b> ou <b>email</b> serão redirecionadas para esse novo empreendimento. Recomendamos que nesse novo empreendimento seja configurado com envio para uma fila de teste dentro do seu CRM.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab">
                                <div class="intro">
                                    <p>Nesta aba, você pode definir quais destinatários receberão os leads relacionados a este produto.</p>
                                </div>
                                <div class="emails">
                                    <div class="all-emails">
                                        @if($building->RelationDestinatarios->first())
                                            @foreach($building->RelationDestinatarios as $index => $destinatarios)
                                            <div class="single-email">
                                                <div class="content-email">
                                                    <div class="form-floating w-100"> 
                                                        <input type="email" class="form-control" id="email-{{ $index }}" name="email[]" value="{{ $destinatarios->email }}" required> 
                                                        <label for="email-{{ $index }}">E-mail <abbr>*</abbr></label> 
                                                    </div>
                                                    <a href="#" class="btn btn-sm btn-outline-danger" onclick="removeEmail(this);">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div>
                                        <a href="#" onclick="addEmail()"><i class="bi bi-envelope-plus pe-1"></i> Cadastrar novo destinatário</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-googlesheets" role="tabpanel" aria-labelledby="nav-googlesheets-tab">
                                <div class="intro">
                                    <p>Nesta aba, você pode configurar o envio automático dos dados para uma planilha do Google Sheets, garantindo mais praticidade e organização.</p>
                                </div>
                                 <div class="sheets">
                                    <div class="all-sheets">
                                        @if($building->RelationSheets->first())
                                            @foreach($building->RelationSheets as $index => $googlesheets)
                                                <div class="single-sheet">
                                                    <div class="content-sheet">
                                                        <div class="row row-gap-2">
                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-floating"> 
                                                                    <input type="text" class="form-control" id="spreadsheetID-{{ $index }}" name="spreadsheetID[]" value="{{ $googlesheets->spreadsheetID }}" required> 
                                                                    <label for="spreadsheetID-{{ $index }}">ID do Sheet <abbr>*</abbr></label> 
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-floating"> 
                                                                    <input type="text" class="form-control" id="sheet-{{ $index }}" name="sheet[]" value="{{ $googlesheets->sheet }}" required> 
                                                                    <label for="sheet-{{ $index }}">Aba da planilha <abbr>*</abbr></label> 
                                                                </div>
                                                            </div>
                                                            <div class="col-12 d-flex gap-3 align-items-center">
                                                                <div class="form-floating w-100"> 
                                                                    <input type="file" class="form-control" id="file-{{ $index }}" name="file[]" onchange="alternateName(this);" {{ $googlesheets->file ? "" : "required" }}> 
                                                                    <input type="hidden" name="fileexists[]" value="{{$googlesheets->file}}">
                                                                    @if($googlesheets->file)
                                                                        <div class="file-exists">{{$googlesheets->file}}</div>
                                                                    @endif
                                                                    <label for="file-{{ $index }}">File de autenticação (JSON) {{ $googlesheets->file ? "" : "<abbr>*</abbr>" }} </label> 
                                                                </div>
                                                                <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeSheet(this);"><i class="bi bi-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div>
                                        <a href="#" onclick="addSheet()"><i class="bi bi-folder-plus pe-1"></i> Cadastrar novo sheets</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-integrations" role="tabpanel" aria-labelledby="nav-integrations-tab">
                                <div class="intro">
                                    <p>Nesta aba, você pode ajustar os processos de integração que serão acionados ao receber um lead, possibilitando o envio para um CRM ou outra ferramenta.</p>
                                </div>
                                <div class="integrations">
                                    <div class="all-integration">
                                        @if($building->RelationIntegrations->first())
                                            @foreach($building->RelationIntegrations as $count => $buildingIntegration)
                                                <div class="single-integration"> 
                                                    <div class="content-integration"> 
                                                        <div class="form-floating"> 
                                                            <select class="form-select" aria-label="Defina uma integração" name="array[{{ $count }}][nameIntegration]" id="integration-{{ $count }}" required> 
                                                                <option selected></option> 
                                                                @if($integrations) 
                                                                    @foreach($integrations as $index => $arr) 
                                                                        @can('access_komuh') 
                                                                            <optgroup label="{{ $index }}"> 
                                                                        @endcan 
                                                                            @foreach($arr as $integration) 
                                                                                <option value="{{ $integration->id }}" {{ $integration->id == $buildingIntegration->id ? "selected" : '' }}>{{ $integration->name }}</option> 
                                                                            @endforeach 
                                                                        @can('access_komuh') 
                                                                            </optgroup> 
                                                                        @endcan
                                                                    @endforeach 
                                                                @endif 
                                                            </select> 
                                                            <label for="integration-{{ $count }}">Integrações <abbr>*</abbr></label> 
                                                        </div> 
                                                        @if($building->RelationIntegrationsFields)
                                                            @foreach($building->RelationIntegrationsFields as $i => $b)
                                                                @if($b->integrations_id === $buildingIntegration->id)
                                                                    <div class="row row-gap-2"> 
                                                                        <div class="input-field col-lg-6 col-12"> 
                                                                            <div class="form-floating"> 
                                                                                <input type="text" class="form-control" id="integrationFieldName-{{$i}}" name="array[{{$count}}][nameField][]" value="{{ $b->name }}" required> 
                                                                                <label for="integrationFieldName-{{$i}}">Nome do campo <abbr>*</abbr></label> 
                                                                            </div> 
                                                                        </div> 
                                                                        <div class="input-field col-lg-6 col-12 d-flex align-items-center gap-2"> 
                                                                            <div class="form-floating w-100"> 
                                                                                <input type="text" class="form-control" id="integrationFieldValor-{{$i}}" name="array[{{$count}}][valueField][]" value="{{ $b->value }}" required> 
                                                                                <label for="integrationFieldValor-{{$i}}">Valor <abbr>*</abbr></label> 
                                                                            </div> 
                                                                            <a href="#" class="btn btn-sm btn-outline-dark rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remover campo" onclick="removeField(this);"><i class="bi bi-dash"></i></a> 
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div> 
                                                    <div class="d-flex gap-2"> 
                                                        <a href="#" class="btn btn-sm btn-outline-dark" onclick="addField(this, {{$count}});"><i class="bi bi-plus"></i> Novo campo</a> 
                                                        <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeIntegration(this);"><i class="bi bi-trash"></i> Excluir integração</a>
                                                    </div> 
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div>
                                        <a href="#" onclick="addIntegration()" class="d-block mb-1"><i class="bi bi-node-plus pe-1"></i> Cadastrar nova integração</a>
                                        <a href="#" class="" data-bs-toggle="modal" data-bs-target="#modalInfo"><i class="bi bi-info-circle pe-1"></i> Mais informações sobre o preenchimento dos campos</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-meta" role="tabpanel" aria-labelledby="nav-meta-tab">
                                <div class="intro">
                                    <p>Nesta aba, você pode definir e otimizar o recebimento de leads diretamente da plataforma do Meta Ads, garantindo uma integração ágil e eficiente.</p>
                                </div>
                                <div class="meta">
                                    <div class="all-meta"></div>
                                    <div>
                                        <a href="#" onclick="addMeta()"><i class="bi bi-bag-plus-fill pe-1"></i> Cadastrar novo formulário</a>
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
                <span class="badge text-bg-secondary">$com</span>
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
                <span class="badge text-bg-secondary">$idCaso</span>
            </div>
            <p class="mb-0">Segue o exemplo de como utilizar uma das variáveis:</p>
            <small class="fw-bold">$nomeCompleto </small>
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
    // Alterando nome do file no sheets
    function alternateName(element) {
        event.preventDefault();
        $(element).next('.file-exists').hide();
    }

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

    var Cpartners = {{ $building->RelationPartners ? count($building->RelationPartners) : 1 }};
    var Cdestinatarios = {{ $building->RelationDestinatarios ? count($building->RelationDestinatarios) : 1 }};
    var Csheets = {{ $building->RelationSheets ? count($building->RelationSheets) : 1 }};
    var Cfields = {{ $building->RelationIntegrationsFields ? count($building->RelationIntegrationsFields) : 1 }};
    var count = Cpartners + Cdestinatarios + Csheets + Cfields + 1;
    var integration = {{ count($building->RelationIntegrations) + 1 }};

    // Partners
    function addPartner() {
        event.preventDefault();
        $('.partners').find('.all-partners').append(`<div class="single-partner"> <div class="content-partner"> <div class="row row-gap-2"> <div class="input-field col-lg-5 col-12"> <div class="form-floating"> <select class="form-select" aria-label="Defina uma empresa" name="partner[]" id="company-` + count + `" required> <option selected></option> @foreach($companies as $company) <option value="{{ $company->id }}">{{ $company->name }}</option> @endforeach </select> <label for="company-` + count + `">Empresas <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-3 col-12"> <div class="form-floating"> <select class="form-select leads" aria-label="Defina a quantidade de leads" name="leads[]" id="leads-` + count + `" required> <option value="99" selected>-</option> <option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> <option value="9">9</option> <option value="10">10</option> </select> <label for="leads-` + count + `">Quantidade de leads <abbr>*</abbr></label> </div> </div> <div class="input-field col-lg-4 col-12 d-flex align-items-center gap-3"> <div class="form-floating w-100"> @cannot('access_komuh') <input type="hidden" name="main[]" value="0"> @endcannot <select class="form-select principal" aria-label="Defina o dono" name="main[]" id="main-` + count + `" @cannot('access_komuh') disabled @else required @endcan> <option value="1">Sim</option> <option value="0" selected>Não</option> </select> <label for="main-` + count + `">Principal <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removePartner(this);"><i class="bi bi-trash"></i></a> </div> </div> </div> </div>`);
        count++;

        leadsPartner();

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
            leadsPartner();
        }
    }
    function leadsPartner(){
        // Removendo - da quantidade de leads
        let partners = $('.all-partners').find('.leads').length;
        if( partners > 1 ) {
            $('.all-partners').find('.leads').find('option[value=99]').remove();
        }   else {
            $('.all-partners').find('.leads').prepend('<option value="99" selected>Todos</option> ');
        }
    }

    // Emails
    function addEmail() {
        event.preventDefault();
        $('.emails').find('.all-emails').append(`<div class="single-email"> <div class="content-email"> <div class="form-floating w-100"> <input type="email" class="form-control" id="email-` + count + `" name="email[]" required> <label for="email-` + count + `">E-mail <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-danger" onclick="removeEmail(this);"> <i class="bi bi-trash"></i> </a> </div> </div>`);
        count++;
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
        $('.sheets').find('.all-sheets').append(`<div class="single-sheet"> <div class="content-sheet"> <div class="row row-gap-2"> <div class="col-lg-6 col-12"> <div class="form-floating"> <input type="text" class="form-control" id="spreadsheetID-` + count + `" name="spreadsheetID[]" required> <label for="spreadsheetID-` + count + `">ID do Sheet <abbr>*</abbr></label> </div> </div> <div class="col-lg-6 col-12"> <div class="form-floating"> <input type="text" class="form-control" id="sheet-` + count + `" name="sheet[]" required> <label for="sheet-` + count + `">Aba da planilha <abbr>*</abbr></label> </div> </div> <div class="col-12 d-flex gap-3 align-items-center"> <div class="form-floating w-100"> <input type="file" class="form-control" id="file-` + count + `" name="file[]" required> <label for="file-` + count + `">File de autenticação (JSON) <abbr>*</abbr></label> </div> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeSheet(this);"><i class="bi bi-trash"></i></a> </div> </div> </div> </div>`);
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
        $('.integrations').find('.all-integration').append(`<div class="single-integration"> <div class="content-integration"> <div class="form-floating"> <select class="form-select" aria-label="Defina uma integração" name="array[` + integration + `][nameIntegration]" id="integration-` + integration + `" required> <option selected></option> @if($integrations) @foreach($integrations as $index => $arr) @can('access_komuh') <optgroup label="{{ $index }}"> @endcan @foreach($arr as $integration) <option value="{{ $integration->id }}">{{ $integration->name }}</option> @endforeach @can('access_komuh') </optgroup> @endcan @endforeach @endif </select> <label for="integration-` + integration + `">Integrações <abbr>*</abbr></label> </div> </div> <div class="d-flex gap-2"> <a href="#" class="btn btn-sm btn-outline-dark" onclick="addField(this, ` + integration + `);"><i class="bi bi-plus"></i> Novo campo</a> <a href="#" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeIntegration(this);"><i class="bi bi-trash"></i> Excluir integração</a></div> </div>`);
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
    }
    function removeField(element) {
        event.preventDefault();
        $(element).closest('.row').remove();
    }

    // Active empreendimento de teste
    $('#check_buildings_id').on('change', function(){
        if( $(this).is(':checked') ) {
            $('#buildings_id').removeAttr('disabled');
            $('#buildings_id').parent().show();
        } else {
            $('#buildings_id').attr('disabled', '');
            $('#buildings_id').parent().hide();
        }
    });
</script>
@endsection