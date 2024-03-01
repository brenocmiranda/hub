@section('title')
Login
@endsection

@section('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/verify.css') }}" rel="stylesheet">
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
                <div class="text-center message-verify">
                    <div class="mb-3">
                        <img src="https://github.com/mdo.png" alt="Imagem usuário" class="rounded-circle" height="80" width="80">
                    </div>
                    <h4>Olá, {{ $user->name }}!</h4>
                    <p>Digite abaixo sua nova senha para redefinição:</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger col-12">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="formulario">
                    <form action="{{ route('reset.password') }}" method="POST">
                        @csrf
                        
                        <div class="input-field">
                            <input type="hidden" name="token" value="{{ $user->remember_token }}">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" aria-label="Nova senha" value="{{ old('password') }}" required>
                                <label for="password">Nova senha</label>
                            </div>
                            @error('password')
                                <div class="mt-2 text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-field">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" aria-label="Confirmação de senha" value="{{ old('password_confirmation') }}" required>
                                <label for="password_confirmation">Confirme sua senha</label>
                            </div>
                            @error('password_confirmation')
                                <div class="mt-2 text-danger">{{ $message }}</div>
                            @enderror
                            <div id="err" class="pt-3"></div>
                        </div>
                        <div class="submit-field mt-3 text-center">
                            <input type="submit" name="submit" id="submit" class="btn btn-dark btn-block form-control" value="Salvar" disabled/>
                        </div>
                    </form>
                </div>
                <div class="mt-3 text-center">
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
@extends('base.footer')

@section('js')
<script>
    $('#password, #password_confirmation').on('keyup', function(){
        console.log($('#password').val());
        console.log($('#password_confirmation').val());
        $('#err').html('');
        // Se confere com a confirmação
        if($('#password').val() == $('#password_confirmation').val()){
            // Se possui no mínimo 8 caracteres
            if($('#password_confirmation').val().length >= 8){
                // Se possui números
                if($('#password_confirmation').val().match(/\d+/)){
                    // Se possui caracteres especiais
                    if($('#password_confirmation').val().match(/[^a-zA-Z0-9]+/)){
                        $('#submit').removeAttr('disabled');
                    }else{
                        $('#err').html('<div class="text-danger text-center col">Sua senha deve conter caracteres especiais.</div>');
                    }
                }else{
                    $('#err').html('<div class="text-danger text-center col">Sua senha deve conter números.</div>');
                }
            }else{
                $('#err').html('<div class="text-danger text-center col"> São necessários no mínimo 8 caracteres.</div>');
            }
        }else{
            $('#err').html('<div class="text-danger text-center col">As senhas não conferem.</div>')
            $('#submit').attr('disabled', 'disabled');
        }
	});
</script>
@endsection