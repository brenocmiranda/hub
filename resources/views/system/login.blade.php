@section('title')
Login
@endsection

@section('css')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@include('base.header')
<main>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-5 column-image">
                <div class="image"></div>
            </div>
            <div class="col-lg-5 col-md-7 col-12 column-form">
                <div class="logo mb-5">
                    <img src="{{ asset('images/logo-black.png') }}" loading="lazy" alt="Hub Integrações"
                        class="img-fluid">
                </div>
                <div class="formulario">

                    @if(session('mailto'))
                        <div class="alert alert-success col-12">
                           <p class="mb-0">O e-mail de redefinição foi enviado com sucesso, verifique a sua caixa de entrada ou spam.</p>
                        </div>
                    @endif

                    @if(session('reset'))
                        <div class="alert alert-success col-12">
                           <p class="mb-0">A sua senha foi redefinida com sucesso, faça login.</p>
                        </div>
                    @endif

                    <form action="{{ route('authentication') }}" method="POST">
                        @csrf

                        @error('active')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="input-field">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-label="E-mail" value="{{ old('email') }}" required>
                                <label for="email">E-mail</label>
                            </div>
                            @error('email')
                                <div class="mt-2 text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-field">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" aria-label="Password" value="{{ old('password') }}" required>
                                <label for="email">Senha</label>
                            </div>
                            @error('password')
                            <div class="mt-2 text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="remember-field">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Continuar conectado
                                </label>
                            </div>
                        </div>
                        <div class="submit-field mt-4 text-center">
                            <input type="submit" name="submit" id="submit" class="btn btn-dark btn-block form-control"
                                value="Acessar" />
                        </div>
                    </form>
                </div>
                <div class="mt-5 text-center">
                    <div>
                        <a href="{{ route('recovery.password') }}" target="_self">
                            Esqueceu a sua senha?
                        </a>
                    </div>
                    <div class="copyright">
                        <small>© Komuh {{date('Y')}} &bull; Version {{ env('APP_VERSION') }} </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>