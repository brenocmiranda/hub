@extends('base.index')

@section('title')
Editar chave
@endsection

@section('content-page')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p> Efetue a alteração das informações preenchendo os campos abaixo: </p>
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
                    
                    <form action="{{ route('products.keys.update', $key->id) }}" method="POST" class="row row-gap-3">
                        @method('PUT')
                        @csrf  
                        
                        <div class="input-field col-lg-12 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('product') is-invalid @enderror" aria-label="Defina um produto" name="product" id="product" required>
                                    <option selected></option>
                                    @if($array)
                                        @foreach($array as $index => $arr)
                                            @can('access_komuh')
                                                <optgroup label="{{ $index }}"> 
                                            @endcan
                                                @foreach($arr as $product)
                                                    <option value="{{ $product->id }}" {{ (old('product') != null && old('product') == $product->id) || $product->id == $key->products_id ? 'selected' : "" }}>{{ $product->name }}</option>
                                                @endforeach
                                            @can('access_komuh')
                                                </optgroup>
                                            @endcan
                                        @endforeach
                                    @endif
                                </select>
                                <label for="product">Produtos <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ $key->value ? $key->value : old('value') }}" required>
                                <label for="value">Value <abbr>*</abbr></label>
                            </div>
                        </div>
                        <div class="input-field col-lg-6 col-12">
                            <div class="form-floating">
                                <select class="form-select @error('active') is-invalid @enderror" aria-label="Defina um status" name="active" id="active" required>
                                    <option selected></option>
                                    <option value="1" {{ (old('active') != null && old('active') == true) || $key->active == true ? 'selected' : "" }}>Ativo</option>
                                    <option value="0" {{ (old('active') != null && old('active') == false) || $key->active == false ? 'selected' : "" }}>Desativado</option>
                                </select>
                                <label for="active">Status <abbr>*</abbr></label>
                            </div>
                        </div>
                        
                        <div class="submit-field d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('products.keys.index') }}"> <i class="bi bi-arrow-left px-2"></i>Voltar</a>
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

