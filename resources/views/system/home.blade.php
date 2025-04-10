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
                <h2 class="text-body-tertiary fw-bold">Veja as novidades do {{ env('APP_NAME') }}</h2>
                <div class="accordion mt-4 text-start" id="accordionItems">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                <div class="d-flex flex-wrap">
                                    <span>30/10/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Meta Ads <span class="badge text-bg-primary mx-2">New</span></span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>Ao analisar os leads que estamos recebendo, foi percebido uma duplicidade na entrada dos mesmos, nesse sentido fizemos um ajuste na nossa API para validar se o email, telefone e produtos já foram cadastrados nos últimos 10 minutos, caso já esteja, entrará como apenas como log "Nova tentativa de contato em duplicidade".</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                <div class="d-flex flex-wrap">
                                    <span>30/10/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Melhorias do cadastro e edição de produtos <span class="badge text-bg-primary mx-2">New</span></span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>Ao analisar os leads que estamos recebendo, foi percebido uma duplicidade na entrada dos mesmos, nesse sentido fizemos um ajuste na nossa API para validar se o email, telefone e produtos já foram cadastrados nos últimos 10 minutos, caso já esteja, entrará como apenas como log "Nova tentativa de contato em duplicidade".</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                <div class="d-flex flex-wrap">
                                    <span>30/10/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Conversão de nomenclaturas <span class="badge text-bg-primary mx-2">New</span></span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>Ao analisar os leads que estamos recebendo, foi percebido uma duplicidade na entrada dos mesmos, nesse sentido fizemos um ajuste na nossa API para validar se o email, telefone e produtos já foram cadastrados nos últimos 10 minutos, caso já esteja, entrará como apenas como log "Nova tentativa de contato em duplicidade".</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                <div class="d-flex flex-wrap">
                                    <span>30/10/2024</span>
                                    <span class="mx-2">-</span>
                                    <span class="fw-semibold">Duplicação de leads</span>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionItems">
                            <div class="accordion-body">
                                <p>Ao analisar os leads que estamos recebendo, foi percebido uma duplicidade na entrada dos mesmos, nesse sentido fizemos um ajuste na nossa API para validar se o email, telefone e produtos já foram cadastrados nos últimos 10 minutos, caso já esteja, entrará como apenas como log "Nova tentativa de contato em duplicidade".</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection