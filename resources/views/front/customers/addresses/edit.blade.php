@extends('layouts.front.app')
@section('css')
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/main_styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart_responsive.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<!-- Main content -->
<br><br><br><br><br><br>
<section class="container content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <div class="row">
            <div class="col">
                @include('layouts.errors-and-messages')
                <div class="cart_title text-center">Editar Endereço &nbsp;</div><br><br>
            </div>
        </div>
        <form action="{{ route('customer.address.update', [$customer->Id, $address->id]) }}" method="post" class="form" enctype="multipart/form-data">
            <input type="hidden" name="status" value="1">
            <input type="hidden" id="address_country_id" value="{{ $address->estado }}">
            <input type="hidden" id="address_city" value="{{ $address->cidade }}">
            <input type="hidden" name="_method" value="put">
            <div class="box-body">
                {{ csrf_field() }}
                <div class="form-group col-md-6">
                    <label for="address_1">Endereço <span class="text-danger">*</span></label>
                    <input type="text" name="endereco" id="address_1" placeholder="" class="form-control" value="{{ old('endereco') ?? $address->endereco }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="alias">Tipo <span class="text-danger">*</span></label>
                    <input type="text" name="tipo" id="alias" placeholder="Casa ou Trabalho" class="form-control" value="{{ old('tipo') ?? $address->tipo }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="address_2">Complemento </label>
                    <input type="text" name="complemento" id="address_2" placeholder="" class="form-control" value="{{ old('complemento') ?? $address->complemento }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="country_id">Estado </label>
                    <select name="estado" id="country_id" class="form-control">
                        <option value="AL" {{($address->estado == 'AL') ? 'selected' : ''}}>Alagoas</option>
                        <option value="AP" {{($address->estado == 'AP') ? 'selected' : ''}}>Amapá</option>
                        <option value="AM" {{($address->estado == 'AM') ? 'selected' : ''}}>Amazonas</option>
                        <option value="BA" {{($address->estado == 'BA') ? 'selected' : ''}}>Bahia</option>
                        <option value="CE" {{($address->estado == 'CE') ? 'selected' : ''}}>Ceará</option>
                        <option value="DF" {{($address->estado == 'DF') ? 'selected' : ''}}>Distrito Federal</option>
                        <option value="ES" {{($address->estado == 'ES') ? 'selected' : ''}}>Espírito Santo</option>
                        <option value="GO" {{($address->estado == 'GO') ? 'selected' : ''}}>Goiás</option>
                        <option value="MA" {{($address->estado == 'MA') ? 'selected' : ''}}>Maranhão</option>
                        <option value="MT" {{($address->estado == 'MT') ? 'selected' : ''}}>Mato Grosso</option>
                        <option value="MS" {{($address->estado == 'MS') ? 'selected' : ''}}>Mato Grosso do Sul</option>
                        <option value="MG" {{($address->estado == 'MG') ? 'selected' : ''}}>Minas Gerais</option>
                        <option value="PA" {{($address->estado == 'PA') ? 'selected' : ''}}>Pará</option>
                        <option value="PB" {{($address->estado == 'PB') ? 'selected' : ''}}>Paraíba</option>
                        <option value="PR" {{($address->estado == 'PR') ? 'selected' : ''}}>Paraná</option>
                        <option value="PE" {{($address->estado == 'PE') ? 'selected' : ''}}>Pernambuco</option>
                        <option value="PI" {{($address->estado == 'PI') ? 'selected' : ''}}>Piauí</option>
                        <option value="RJ" {{($address->estado == 'RJ') ? 'selected' : ''}}>Rio de Janeiro</option>
                        <option value="RN" {{($address->estado == 'RN') ? 'selected' : ''}}>Rio Grande do Norte</option>
                        <option value="RS" {{($address->estado == 'RS') ? 'selected' : ''}}>Rio Grande do Sul</option>
                        <option value="RO" {{($address->estado == 'RO') ? 'selected' : ''}}>Rondônia</option>
                        <option value="RR" {{($address->estado == 'RR') ? 'selected' : ''}}>Roraima</option>
                        <option value="SC" {{($address->estado == 'SC') ? 'selected' : ''}}>Santa Catarina</option>
                        <option value="SP" {{($address->estado == 'SP') ? 'selected' : ''}}>São Paulo</option>
                        <option value="SE" {{($address->estado == 'SE') ? 'selected' : ''}}>Sergipe</option>
                        <option value="TO" {{($address->estado == 'TO') ? 'selected' : ''}}>Tocantins</option>
                        <option value="EX" {{($address->estado == 'EX') ? 'selected' : ''}}>Estrangeiro</option>
                    </select>
                </div>
                <div id="cidade" class="form-group">
                    <div class="form-group col-md-3">
                        <label for="">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="form-control" value="{{ old('cidade') ?? $address->cidade }}">
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="zip">Cep </label>
                    <input type="text" name="cep" id="cep" placeholder="00000-000" class="form-control" value="{{ old('cep') ?? $address->cep }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="phone">Telefone </label>
                    <input type="text" name="telefone" id="telefone" placeholder="" class="form-control" value="{{ old('telefone') ?? $address->telefone }}">
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
