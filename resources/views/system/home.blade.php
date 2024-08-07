@extends('base.index')

@section('title')
Home
@endsection

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content-page')
    <div class="container">
        <div class="row gap-3 justify-content-center">
            <div class="col-xl-8 col-lg-9 col-md-10 col-12 text-center">
                <i class="text-body-tertiary bi bi-question-circle"></i>
                <h2 class="text-body-tertiary fw-bold">Veja as novidades do Hub</h2>
                <div class="accordion mt-4 text-start" id="accordionItems">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="false" aria-controls="flush-collapse1">
                                <div class="d-flex flex-wrap">
                                    <span>06/08/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Níveis de acesso<span class="badge text-bg-primary mx-2">New</span></span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse1" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>Garantindo a segurança das informações, foram criados os níveis de acessos para cada usuário, onde os administradores poderão ajustar de acordo com suas necessidades.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse2" aria-expanded="false" aria-controls="flush-collapse2">
                                <div class="d-flex flex-wrap">
                                    <span>06/08/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Leads de teste<span class="badge text-bg-primary mx-2">New</span></span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>O processo de detecção de leads de teste foi implantada e agora você poderá escolher um empreendimento para serem redirecionados os leads provenientes de testes.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                <div class="d-flex flex-wrap">
                                    <span>05/08/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Roleta de leads</span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>A roleta dos leads entre responsáveis já está disponível e no cadastro do empreendimento você consegue ajustar as configurações.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-bottom-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                <div class="d-flex flex-wrap">
                                    <span>05/08/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Criação de relatórios</span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>Com essa nova versão, você poderá exportar os leads em PDF, XLS e CSV. Poderá utilizar os filtros disponíveis para retornar apenas o período desejado, selecionar de qual empreendimento deseja e entre outros.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection