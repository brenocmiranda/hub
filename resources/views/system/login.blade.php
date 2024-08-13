@section('title')
Login
@endsection

@section('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@include('base.header')
<main>
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-9 col-lg-12 col-11">
                <div class="row px-4 py-5 rounded-4 bg-white card-login">
                    <div class="col-lg-6 col-12">
                        <div class="logo mb-4">
                            <img src="{{ asset('images/favicon2.ico') }}" loading="lazy" alt="Hub Integrações"
                                class="img-fluid">
                        </div>
                        <div>
                            <h2>Fazer login</h2>
                            <p>Entrar no Hub de Integrações.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="formulario mt-lg-5 mt-4 pt-2">
                            @if(session('mailto'))
                                <div class="alert alert-success col-12">
                                <p class="mb-0">O e-mail de redefinição foi enviado com sucesso, verifique a sua caixa de entrada ou spam.</p>
                                </div>
                            @endif

                            @if(session('reset'))
                                <div class="alert alert-success col-12">
                                <p class="mb-0">A sua senha foi alterada com sucesso, faça login.</p>
                                </div>
                            @endif

                            <form action="{{ route('authentication') }}" method="POST">
                                @csrf

                                @error('active')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="input-field mb-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-label="E-mail" value="{{ old('email') }}" placeholder="" required>
                                        <label for="email">E-mail</label>
                                    </div>
                                    @error('email')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-field mb-2">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" aria-label="Password" value="{{ old('password') }}" placeholder="" required>
                                        <a href="#" class="bi bi-eye-fill view-password"></a>
                                        <label for="email">Senha</label>
                                    </div>
                                    @error('password')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <a href="{{ route('recovery.password') }}" target="_self">
                                        Esqueceu sua senha?
                                    </a>
                                </div>
                                <div class="submit-field text-end mt-5">
                                    <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 rounded-5" value="Acessar" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4 col-sm-5 col-12 d-none d-sm-block text-secondary">
                        <small>© KGroup {{date('Y')}} &bull; Version {{ env('APP_VERSION') }} </small>
                    </div>
                    <div class="col-lg-8 col-sm-7 col-12 text-sm-end text-center px-0">
                        <!--<a href="https://komuh.com/" target="_blank" class="btn btn-sm btn-outline-gray rounded-3 border-0 px-3"> <small>Komuh</small> </a>-->
                        <a href="https://drive.google.com/file/d/144pvmzqW4E5xt6-AG1bt7cPPRyhx88Zz/view?usp=drive_link" target="_blank" class="btn btn-sm btn-outline-gray rounded-3 border-0 px-3"> <small>Documentação</small> </a>
                        <a href="mailto:breno.miranda@komuh.com" target="_blank" class="btn btn-sm btn-outline-gray rounded-3 border-0 px-3"> <small>Ajuda</small> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@extends('base.footer')

@section('js')
<script>
    $('.view-password').on('click', function(e){
        e.preventDefault();
        if( $(this).prev('input').attr('type') == 'password' ){
            $(this).addClass('bi-eye-slash-fill');
            $(this).removeClass('bi-eye-fill');
            $(this).prev('input').attr('type', 'text');
        } else {
            $(this).addClass('bi-eye-fill');
            $(this).removeClass('bi-eye-slash-fill');
            $(this).prev('input').attr('type', 'password');
        }
	});
</script>
@endsection