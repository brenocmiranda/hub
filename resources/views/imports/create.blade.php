@extends('base.index')

@section('title')
Nova importação
@endsection

@section('css')
    <link href="{{ asset('css/imports.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="leads-tab" data-bs-toggle="tab" data-bs-target="#leads-tab-pane" type="button" role="tab" aria-controls="leads-tab-pane" aria-selected="true">Leads</button>
                    </li>
                </ul>
                <div class="tab-content p-4 border border-secondary-subtle bg-white rounded" id="myTabContent">
                    <div class="tab-pane fade show active" id="leads-tab-pane" role="tabpanel" aria-labelledby="leads-tab" tabindex="0">
                        <form action="{{ route('reports.store') }}" class="row" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="leads">
                            <div class="col-lg-6 col-12">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-secondary py-2 text-white">Nome do campo</th>
                                            <th class="text-center bg-secondary py-2 text-white">Nome da coluna</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div> 
                                                    <select class="form-select rounded-0" name="array[]" disabled> 
                                                        <option value="name" selected>Nome</option> 
                                                        <option value="email">Email</option> 
                                                        <option value="phone">Telefone</option> 
                                                        <option value="companie">Empresa</option> 
                                                        <option value="origin">Origem</option> 
                                                        <option value="building">Empreendimento</option>
                                                        <option value="utm_source">Utm_source</option> 
                                                        <option value="utm_medium">Utm_medium</option> 
                                                        <option value="utm_campaign">Utm_campaign</option> 
                                                        <option value="utm_term">Utm_term</option> 
                                                        <option value="utm_content">Utm_content</option> 
                                                        <option value="gclid">Gclid</option> 
                                                        <option value="fbclid">Fbclid</option> 
                                                        <option value="plataforma">Plataforma</option>  
                                                        <option value="message">Mensagem</option> 
                                                        <option value="url">URL</option> 
                                                        <option value="pp">pp <small>(Política de privacidade)</small></option> 
                                                        <option value="com">com <small>(Receber comunicações)</small></option>
                                                    </select> 
                                                </div> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control rounded-0" id="name" name="name" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div> 
                                                    <select class="form-select rounded-0" name="array[]" disabled> 
                                                        <option value="name">Nome</option> 
                                                        <option value="email" selected>Email</option> 
                                                        <option value="phone">Telefone</option> 
                                                        <option value="companie">Empresa</option> 
                                                        <option value="origin">Origem</option> 
                                                        <option value="building">Empreendimento</option>
                                                        <option value="utm_source">Utm_source</option> 
                                                        <option value="utm_medium">Utm_medium</option> 
                                                        <option value="utm_campaign">Utm_campaign</option> 
                                                        <option value="utm_term">Utm_term</option> 
                                                        <option value="utm_content">Utm_content</option> 
                                                        <option value="gclid">Gclid</option> 
                                                        <option value="fbclid">Fbclid</option> 
                                                        <option value="plataforma">Plataforma</option>  
                                                        <option value="message">Mensagem</option> 
                                                        <option value="url">URL</option> 
                                                        <option value="pp">pp <small>(Política de privacidade)</small></option> 
                                                        <option value="com">com <small>(Receber comunicações)</small></option>
                                                    </select> 
                                                </div> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control rounded-0" id="email" name="email" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div> 
                                                    <select class="form-select rounded-0" name="array[]" disabled> 
                                                        <option value="name">Nome</option> 
                                                        <option value="email">Email</option> 
                                                        <option value="phone" selected>Telefone</option> 
                                                        <option value="companie">Empresa</option> 
                                                        <option value="origin">Origem</option> 
                                                        <option value="building">Empreendimento</option>
                                                        <option value="utm_source">Utm_source</option> 
                                                        <option value="utm_medium">Utm_medium</option> 
                                                        <option value="utm_campaign">Utm_campaign</option> 
                                                        <option value="utm_term">Utm_term</option> 
                                                        <option value="utm_content">Utm_content</option> 
                                                        <option value="gclid">Gclid</option> 
                                                        <option value="fbclid">Fbclid</option> 
                                                        <option value="plataforma">Plataforma</option>  
                                                        <option value="message">Mensagem</option> 
                                                        <option value="url">URL</option> 
                                                        <option value="pp">pp <small>(Política de privacidade)</small></option> 
                                                        <option value="com">com <small>(Receber comunicações)</small></option>
                                                    </select> 
                                                </div> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control rounded-0" id="phone" name="phone" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div> 
                                                    <select class="form-select rounded-0" name="array[]" disabled> 
                                                        <option value="name">Nome</option> 
                                                        <option value="email">Email</option> 
                                                        <option value="phone">Telefone</option> 
                                                        <option value="companie" selected>Empresa</option> 
                                                        <option value="origin">Origem</option> 
                                                        <option value="building">Empreendimento</option>
                                                        <option value="utm_source">Utm_source</option> 
                                                        <option value="utm_medium">Utm_medium</option> 
                                                        <option value="utm_campaign">Utm_campaign</option> 
                                                        <option value="utm_term">Utm_term</option> 
                                                        <option value="utm_content">Utm_content</option> 
                                                        <option value="gclid">Gclid</option> 
                                                        <option value="fbclid">Fbclid</option> 
                                                        <option value="plataforma">Plataforma</option>  
                                                        <option value="message">Mensagem</option> 
                                                        <option value="url">URL</option> 
                                                        <option value="pp">pp <small>(Política de privacidade)</small></option> 
                                                        <option value="com">com <small>(Receber comunicações)</small></option>
                                                    </select> 
                                                </div> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control rounded-0" id="companie" name="companie" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div> 
                                                    <select class="form-select rounded-0" name="array[]" disabled> 
                                                        <option value="name">Nome</option> 
                                                        <option value="email">Email</option> 
                                                        <option value="phone">Telefone</option> 
                                                        <option value="companie">Empresa</option> 
                                                        <option value="origin" selected>Origem</option> 
                                                        <option value="building">Empreendimento</option>
                                                        <option value="utm_source">Utm_source</option> 
                                                        <option value="utm_medium">Utm_medium</option> 
                                                        <option value="utm_campaign">Utm_campaign</option> 
                                                        <option value="utm_term">Utm_term</option> 
                                                        <option value="utm_content">Utm_content</option> 
                                                        <option value="gclid">Gclid</option> 
                                                        <option value="fbclid">Fbclid</option> 
                                                        <option value="plataforma">Plataforma</option>  
                                                        <option value="message">Mensagem</option> 
                                                        <option value="url">URL</option> 
                                                        <option value="pp">pp <small>(Política de privacidade)</small></option> 
                                                        <option value="com">com <small>(Receber comunicações)</small></option>
                                                    </select> 
                                                </div> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control rounded-0" id="origin" name="origin" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div> 
                                                    <select class="form-select rounded-0" name="array[]" disabled> 
                                                        <option value="name">Nome</option> 
                                                        <option value="email">Email</option> 
                                                        <option value="phone">Telefone</option> 
                                                        <option value="companie">Empresa</option> 
                                                        <option value="origin">Origem</option> 
                                                        <option value="building" selected>Empreendimento</option>
                                                        <option value="utm_source">Utm_source</option> 
                                                        <option value="utm_medium">Utm_medium</option> 
                                                        <option value="utm_campaign">Utm_campaign</option> 
                                                        <option value="utm_term">Utm_term</option> 
                                                        <option value="utm_content">Utm_content</option> 
                                                        <option value="gclid">Gclid</option> 
                                                        <option value="fbclid">Fbclid</option> 
                                                        <option value="plataforma">Plataforma</option>  
                                                        <option value="message">Mensagem</option> 
                                                        <option value="url">URL</option> 
                                                        <option value="pp">pp <small>(Política de privacidade)</small></option> 
                                                        <option value="com">com <small>(Receber comunicações)</small></option>
                                                    </select> 
                                                </div> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control rounded-0" id="building" name="building" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="#" class="d-block text-center mt-2"> <i class="bi bi-plus"></i> Adicionar mais um item </a>
                            </div>
                            <div class="col-lg-6 col-12 mb-3"> 
                                <div>
                                    <input class="form-control rounded-0" type="file" id="formFile" name="import" accept=".xls,.csv,.json">
                                    <small>* O arquivo deve estar no formato .xls, .csv ou .json para importação.</small>
                                </div>
                                <div class="submit-field d-flex justify-content-md-end justify-content-center align-items-center gap-3 mt-3">
                                    <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Importar" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

