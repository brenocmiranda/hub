@section('title')
Solicitação
@endsection

@section('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@include('base.header')
<main>
    <div class="container-fluid">
         <div class="row align-items-center justify-content-center">
            <div class="col-lg-8 col-md-9 col-sm-10 col-11">
                <div class="row px-4 py-5 rounded-4 bg-white card-login">
                    <div class="col-lg-6 col-12">
                        <div class="logo mb-4">
                            <img src="{{ asset('images/favicon2.ico') }}" loading="lazy" alt="Hub Integrações"
                                class="img-fluid">
                        </div>
                        <div>
                            <h2>Encontre seu e-mail</h2>
                            <p>Solicitação de redefinição de senha.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="formulario mt-lg-5 mt-4 pt-2">
                            <form action="{{ route('recovering.password') }}" method="POST">
                                @csrf

                                <div class="input-field">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-label="E-mail" value="{{ old('email') }}" placeholder="" required>
                                        <label for="email">E-mail</label>
                                    </div>
                                    @error('email')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-end align-items-center gap-4 mt-5">
                                    <div>
                                        <a href="{{ route('login') }}"> <i class="bi bi-arrow-left px-2"></i>Login</a>
                                    </div>
                                    <div class="submit-field text-end">
                                        <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 rounded-5" value="Enviar"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4 col-sm-5 col-12 d-none d-sm-block text-secondary">
                        <small>© Komuh {{date('Y')}} &bull; Version {{ env('APP_VERSION') }} </small>
                    </div>
                    <div class="col-lg-8 col-sm-7 col-12 text-sm-end text-center pe-0">
                        <a href="https://komuh.com/" target="_blank" class="btn btn-sm btn-outline-gray rounded-3 border-0 px-3"> <small>Komuh</small> </a>
                        <a href="https://drive.google.com/file/d/144pvmzqW4E5xt6-AG1bt7cPPRyhx88Zz/view?usp=drive_link" target="_blank" class="btn btn-sm btn-outline-gray rounded-3 border-0 px-3"> <small>Documentação</small> </a>
                        <a href="mailto:breno.miranda@komuh.com" target="_blank" class="btn btn-sm btn-outline-gray rounded-3 border-0 px-3"> <small>Ajuda</small> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>