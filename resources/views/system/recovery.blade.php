@section('title')
Esqueceu a senha
@endsection

@section('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@include('base.header')
<main>
    <div class="container-fluid">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-7 col-md-5 column-image">
                <div class="image"></div>
            </div>
            <div class="col-lg-5 col-md-7 col-12 column-form">
                <div class="logo mb-5">
                    <img src="{{ asset('images/logo-black.png') }}" loading="lazy" alt="Hub Integrações"
                        class="img-fluid">
                </div>
                <div class="message-recovery">
                    <p>Preencha os campos abaixo para solicitar a redefinição de senha:</p>
                </div>
                <div class="formulario">
                    <form action="{{ route('recovering.password') }}" method="POST">
                        @csrf

                        <div class="input-field">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-label="E-mail" value="{{ old('email') }}" required>
                                <label for="email">E-mail</label>
                            </div>
                            @error('email')
                                <div class="mt-2 text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="submit-field mt-4 text-center">
                            <input type="submit" name="submit" id="submit" class="btn btn-dark btn-block form-control"
                                value="Solicitar" />
                        </div>
                    </form>
                </div>
                <div class="mt-5 text-center">
                    <div class="mb-5">
                        <a href="{{ route('login') }}"> <i class="bi bi-arrow-left px-2"></i>Login</a>
                    </div>
                    <div class="copyright">
                        <small>© Komuh {{date('Y')}} &bull; Version {{ env('APP_VERSION') }} </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>