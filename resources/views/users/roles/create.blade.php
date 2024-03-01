@extends('base.index')

@section('title')
Nova função
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p> Efetue a criação de um novo cadastro preenchendo as informações abaixo: </p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="formulario">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('store.users.roles') }}" method="POST" class="row row-gap-3">
                        @csrf
                        
                        <div class="input-field col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Komuh" value="{{ old('name') }}" required>
                                <label for="name">Nome</label>
                            </div>
                        </div>
                        <div class="input-field col-6">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" placeholder="Komuh" value="{{ old('value') }}" required>
                                <label for="value">Value</label>
                            </div>
                        </div>
                        <div class="input-field col-6">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ old('active') != null && old('active') == true ? 'selected' : "" }}>Ativo</option>
                                    <option value="0" {{ old('active') != null && old('active') == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status</label>
                            </div>
                        </div>
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('index.users.roles') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
                            <input type="submit" name="submit" id="submit" class="btn btn-dark px-5 py-2" value="Salvar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $('#name').on('keyup', function(){
        $('.slug').val(slugify($(this).val()));
    });
</script>
@endsection

