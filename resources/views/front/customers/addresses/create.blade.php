@section('css')
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/main_styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart_responsive.css') }}" rel="stylesheet" type="text/css">
@endsection

@extends('layouts.front.app')

@section('content')
<br><br><br><br><br><br>

<!-- Main content -->

<section class="container content">
    <div class="row">
        <div class="col">
            @include('layouts.errors-and-messages')
            <div class="cart_title text-center"> Adicionar Endereço</div><br><br>
        </div>
    </div>
    <div class="box">
        @if(!isset($endereco))
        <div class="col-md-4"> </div>
        <div class="form-group col-md-4 ">
            <label for="address_1">Procurar por CEP</label>
            <form action="" method="GET" class="form">
                <input type="text" name="cep" id="address_1" placeholder="00000-000" class="form-control " value="">
                <button type="submit" id="search_button" class="search_button" style="margin: 15px;"><img src="{{ asset('images/magnifying-glass.svg') }} " id="lupa" alt=""></button>
        </div>
        </form>

    </div><br><br>
    <form action="{{ route('customer.address.store', $customer->Id) }}" method="post" class="form" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="status" value="1">
        <div class="box-body">
            <div class="form-group col-md-6">
                <label for="address_1">Endereço <span class="text-danger">*</span></label>
                <input type="text" name="endereco" id="address_1" placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="alias">Tipo <span class="text-danger">*</span></label>
                <input type="text" name="tipo" required="required" id="alias" placeholder="Casa ou Trabalho" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="address_2">Complemento <span class="text-danger">*</span> </label>
                <input type="text" name="complemento" id="address_2" placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="country_id">Estado </label>

                <select name="estado" id="country_id" class="form-control select2">
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                    <option value="EX">Estrangeiro</option>
                </select>
            </div>
            <div id="cidade" class="form-group">
                <div class="form-group col-md-3">
                    <label for="">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="zip">Cep </label>
                <input type="text" name="cep" type="hidden" id="cep" placeholder="00000-000" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="phone">Telefone <span class="text-danger">*</span></label>
                <input type="text" name="telefone" id="telefone" placeholder="(00)00000-0000" class="form-control">
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer col-md-12">
            <div class="cart_control_bar d-flex flex-md-row flex-column align-items-start justify-content-start">
                <button class="button_clear cart_button ml-auto"><a href="{{ route('conta', ['tab' => 'address']) }}">Voltar</a></button>
                <button type="submit" id="right_button" class="button_update cart_button_2">Salvar</button>
            </div>
        </div>
    </form>
    @else
    <form action="{{ route('customer.address.store', $customer->Id) }}" method="post" class="form" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="status" value="1">
        <div class="box-body">
            <div class="form-group col-md-6">
                <label for="address_1">Endereço <span class="text-danger">*</span></label>
                <input type="text" name="endereco" id="address_1" placeholder="" class="form-control" value="{{$endereco->getEndereco()}} - {{$endereco->getBairro()}}">
            </div>
            <div class="form-group col-md-3">
                <label for="alias">Tipo <span class="text-danger">*</span></label>
                <input type="text" name="tipo" required="required" id="alias" placeholder="Casa ou Trabalho" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="address_2">Complemento <span class="text-danger">*</span> </label>
                <input type="text" name="complemento" id="address_2" placeholder="" class="form-control" value="{{$endereco->getComplemento1() != null ? $endereco->getComplemento1() : $endereco->getComplemento2()}}">
            </div>
            <div class="form-group col-md-3">
                <label for="country_id">Estado </label>

                <select name="estado" id="country_id" class="form-control select2">
                    @if(isset($endereco))
                    <option selected="selected" value="{{$endereco->getUf()}}">{{$endereco->getUf()}}</option>
                    @else
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                    <option value="EX">Estrangeiro</option>
                    @endif
                </select>
            </div>
            <div id="cidade" class="form-group">
                <div class="form-group col-md-3">
                    <label for="">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="form-control" value="{{$endereco->getCidade()}}">
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="zip">Cep </label>
                <input type="text" name="cep" type="hidden" value="{{$endereco->getCep()}}" id="cep" placeholder="00000-000" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label for="phone">Telefone <span class="text-danger">*</span></label>
                <input type="text" name="telefone" id="telefone" placeholder="() 00000-0000" class="form-control">
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer col-md-12">
            <div class="cart_control_bar d-flex flex-md-row flex-column align-items-start justify-content-start">
                <button class="button_clear cart_button ml-auto"><a href="{{ route('conta', ['tab' => 'address']) }}">Voltar</a></button>
                <button type="submit" id="right_button" class="button_update cart_button_2">Salvar</button>
            </div>
        </div>
    </form>
    @endif

    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection

@section('css')
<link href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('js')
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $("input[name='cep']").mask('00000-000');
    $("input[name='telefone']").mask('(00)00000-0000');
</script>
@endsection