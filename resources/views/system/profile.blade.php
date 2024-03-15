@extends('base.index')

@section('title')
Meu perfil
@endsection

@section('content-page')
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="row row-gap-3" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="col-12 mb-3">
                        <div class="perfil position-relative m-auto" style="width: 130px; height: 130px">
                            <div class="border border-secondary-subtle rounded-circle PreviewImage">
                                @if(Auth::user()->src)
                                    <img src="{{ Auth::user()->src ? asset('storage/profile/' . Auth::user()->src) : '' }}" alt="" width="130" height="130" class="rounded-circle me-1 object-fit-cover">
                                @else
                                    <div class="rounded-circle bg-secondary me-1 fw-bold text-white d-flex align-items-center justify-content-center" style="width: 130px; height: 130px">
                                        <h2>{{ substr(Auth::user()->name,0,1) }}</h2>
                                    </div>
                                @endif
                            </div>
                            <div class="btn-photo bg-dark opacity-75 rounded-circle position-absolute top-0 start-0 end-0 m-auto" style="width: 130px; height: 130px; display: none; cursor: pointer; transition: all .3s ease;">
                                <input type="file" class="d-none" accept=".jpg, .jpeg, .png, .bmp, .gif, .svg, .webp" name="src" id="src" onchange="image(this);">
                                <label for="src" class="w-100 h-100 d-flex justify-content-center align-items-center">
                                    <i class="bi bi-camera text-white"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="input-field col-6">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ? old('name') : Auth::user()->name }}" required>
                            <label for="name">Nome <abbr>*</abbr></label>
                        </div>
                    </div>

                    <div class="input-field col-6">
                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ? old('email') : Auth::user()->email }}" required>
                            <label for="email">E-mail <abbr>*</abbr></label>
                        </div>
                    </div>

                    <div class="input-field col-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="companie" value="{{ Auth::user()->RelationCompanies->name }}" disabled>
                            <label for="companie">Empresa <abbr>*</abbr></label>
                        </div>
                    </div>

                    <div class="input-field col-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="rule" value="{{ Auth::user()->RelationRules->name }}" disabled>
                            <label for="rule">Tipo de acesso <abbr>*</abbr></label>
                        </div>
                    </div>

                    <div class="col-12 px-4">
                        <a href="javascript:void(0)" id="password"> 
                            <span>Alterar senha</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                    </div>
                    
                    <div class="col-12 d-flex flex-wrap row-gap-3 row-none d-none">
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="*********" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                                <label for="password">Senha <abbr>*</abbr></label>
                            </div>
                        </div>

                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="*********" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                                <label for="password_confirmation">Confirmação de senha <abbr>*</abbr></label>
                            </div>
                        </div>
                    </div>

                    <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('home') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                        <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript">
	$(document).ready( function (){
		$('#password').on('click', function(){
			if($('.row-none').hasClass('d-none')){
				$('.row-none').removeClass('d-none');
			}else{
				$('.row-none').addClass('d-none');
			}
		});

		$('.perfil').hover( function(){
			$('.btn-photo').fadeIn('fast');
		} , function() {
			$('.btn-photo').fadeOut('fast');
		});
	});
</script>
@endsection