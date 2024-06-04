@section('title')
Alteração de senha
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
                            <h2>Alteração de senha</h2>
                            <p>Preencha os campos abaixo para cadastrar sua senha.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="text-center message-verify">
                            <div class="mb-3">
                                 @if($user->src)
                                    <img src="{{ $user->src ? asset('storage/profile/' . $user->src) : '' }}" alt="" width="100" height="100" class="rounded-circle me-1 object-fit-cover">
                                @else
                                    <div class="rounded-circle bg-secondary me-1 fw-bold text-white d-flex align-items-center justify-content-center" style="width: 100px; height: 100px">
                                        <h2>{{ substr($user->name,0,1) }}</h2>
                                    </div>
                                @endif
                            </div>
                            <h4>Olá, {{ $user->name }}!</h4>
                        </div>

                        <div class="formulario mt-4">
                            <form action="{{ route('reset.password') }}" method="POST">
                                @csrf
                                
                                <div class="input-field mb-2">
                                    <input type="hidden" name="token" value="{{ $user->remember_token }}">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" aria-label="Nova senha" value="{{ old('password') }}" placeholder="" required>
                                        <a href="#" class="bi bi-eye-fill view-password"></a>
                                        <label for="password">Nova senha</label>
                                    </div>
                                    @error('password')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="input-field">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" aria-label="Confirmação de senha" value="{{ old('password_confirmation') }}" placeholder="" required>
                                        <a href="#" class="bi bi-eye-fill view-password"></a>
                                        <label for="password_confirmation">Confirme sua senha</label>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                    <div id="err" class="pt-3"></div>
                                </div>
                                
                                <div class="d-flex justify-content-end align-items-center gap-4 mt-4">
                                    <div>
                                        <a href="{{ route('login') }}"> <i class="bi bi-arrow-left px-2"></i>Login</a>
                                    </div>
                                    <div class="submit-field text-end">
                                        <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 rounded-5" value="Salvar" disabled/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4 col-sm-5 col-12 d-none d-sm-block">
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