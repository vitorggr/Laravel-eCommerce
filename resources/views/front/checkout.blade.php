@extends('layouts.front.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{@asset('css/cart.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/cart_responsive.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/checkout.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/style.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/checkout_responsive.css')}}">
@endsection
@section('og')
<meta property="og:type" content="checkout" />
<link rel="canonical" href="http://www.easyshop.com.br/checkout" />
@endsection
@section('content')
<div class="home">
    <div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/categories.jpg" data-speed="0.8"></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="home_container">
                    <div class="home_content">
                        <div class="home_title">Checkout</div>
                        <div class="breadcrumbs">
                            <ul>
                                <ul>
                                    <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Home</a></li>
                                    <li>Carrinho</li>
                                    <li>Pagamento</li>
                                </ul>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Laracom Checkout -->

<div class="container pt-5">
    @include('layouts.coupon-errors-and-messages')
    <div class="row">


        @if(!$products->isEmpty())
        <div class=" col-lg-6 card_checkout">
            @if($descontoGeral == null || $descontoCondicional || null)
            <div class="cart_details">

                <div class="checkout_coupon mb-5">
                    <legend class="checkout_title">Cupom de desconto</legend>
                    <form method="get" action="{{route ('checkout.index')}}" class="cart_coupon_form w-100 d-flex flex-row align-items-start justify-content-start" id="cart_coupon_form">
                        <input type="hidden" name="ativo" value="1">
                        <input type="text" name="cupom" class="cart_coupon_input w-100" placeholder="Adicione o código" required="required">
                        <button class="button_clear cart_button_2 col_12 ">aplicar </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="cart_details">
                <div class="row">
                    @if(isset($addresses) && count($addresses) > 0)
                    <div class="col-md-12">
                        <legend class="checkout_title">Endereço</legend>
                        @if(is_null($newAddress))
                        <form method="get" action="{{route ('checkout.index')}}">


                            <table id="table" class="table table-striped">

                                <tbody>
                                    @foreach($addresses as $key => $address)
                                    <tr>
                                        <td>{{$address->cidade}},{{ $address->endereco }} {{ $address->complemento }} - {{ $address->tipo }} </td>
                                        <td>
                                            <label class="col-md-6 col-md-offset-3">
                                                <input type="radio" value="{{ $address->id }}" name="billing_address" @if($endereco->id == $address->id) checked="checked" @endif>
                                            </label>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <div id="shipping_options">
                                @if(!is_null($shipping))
                                <legend id="" class="checkout_title">Entrega</legend>
                                <table id="table" class="table table-striped">
                                    <tbody>
                                        <tr>
                                            @if($shipping[42]->geterroMsg() == null)
                                            <td><img class="small_picture" src="images/sedex.png" alt="" style="max-width: 80px;">
                                                &nbsp; até {{$shipping[42]->getPrazoEntrega()}} dias - R${{number_format($shipping[42]->getValor(),2,',','.')}} </td>
                                            <td>
                                                <label class="col-md-6 col-md-offset-3">
                                                    <input type="radio" name="price" value="{{$shipping[42]->getValor()}}">
                                                </label>
                                            </td>
                                            @else
                                            <td><img class="small_picture" src="images/sedex.png" alt="" style="max-width: 80px;">
                                                &nbsp; {{$shipping[42]->getErroMsg()}}</td>
                                            <td>
                                                @endif

                                        </tr>
                                        <tr>
                                            @if($shipping[43]->geterroMsg() == null)
                                            <td><img class="small_picture" src="images/pac.png" alt="" style="max-width: 80px;">
                                                &nbsp; até {{$shipping[43]->getPrazoEntrega()}} dias - R${{number_format($shipping[43]->getValor(),2,',','.')}}</td>
                                            <td>
                                                <label class="col-md-6 col-md-offset-3">
                                                    <input type="radio" name="price" value="{{$shipping[43]->getValor()}}">
                                                </label>
                                            </td>
                                            @else
                                            <td><img class="small_picture" src="images/pac.png" alt="" style="max-width: 80px;">
                                                &nbsp; {{explode(": ",$shipping[43]->geterroMsg())[1]}}</td>
                                            <td>
                                                @endif
                                        </tr>
                                    <tbody>
                                </table>

                                @endif
                            </div>

                            <input name="frete" type="hidden" value="true">
                            @if($descontoGeral === true || $descontoCondicional === true)
                            <input name="cupom" type="hidden" value="{{request()->input('cupom')}}">
                            @endif
                            @if(request()->input('frete')==true)
                            <button type="submit" id="margin_button" class="cart_total_button">Recalcular Frete</button>
                            @else
                            <button type="submit" id="margin_button" class="cart_total_button">Calcular Frete</button>
                            @endif
                        </form>
                        <button id="search" class="cart_button col-md-12">Adicionar Novo Endereço</button>
                        @endif

                        <div id="cep_input"></div>
                        @if(!is_null($newAddress))
                        <form action="/checkout/storeaddress" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="address_1">Endereço <span class="text-danger">*</span></label>
                                <input type="text" name="endereco" id="address_1" required placeholder="" class="form-control" value="{{$newAddress->getEndereco()}}">
                            </div>
                            <div class="form-group">
                                <label for="alias">Tipo <span class="text-danger">*</span></label>
                                <input type="text" name="tipo" id="alias" placeholder="Casa ou Trabalho" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="address_2">Complemento <span class="text-danger">*</span> </label>
                                <input type="text" name="complemento" id="address_2" placeholder="" class="form-control" value="{{$newAddress->getComplemento1() != null ? $newAddress->getComplemento1() : $newAddress->getComplemento2()}}" required>
                            </div>
                            <div class="form-group">
                                <label for="country_id">Estado </label>
                                <select name="estado" id="country_id" class="form-control select2" required>
                                    <!-- @if(isset($endereco)) -->
                                    <!-- @endif -->
                                    <option selected="selected" value="{{$newAddress->getUf()}}">{{$newAddress->getUf()}}</option>
                                </select>
                            </div>
                            <div id="cidade" class="form-group">
                                <div class="form-group">
                                    <label for="">Cidade</label>
                                    <input type="text" id="cidade" name="cidade" value="{{$newAddress->getCidade()}}" class="form-control" required>
                                </div>
                            </div>
                            <div id="bairro" class="form-group">
                                <div class="form-group">
                                    <label for="">Bairro</label>
                                    <input type="text" id="bairro" name="bairro" value="{{$newAddress->getBairro()}}" class="form-control" required>
                                </div>
                            </div>
                            <input type="hidden" name="cep" value="{{$newAddress->getCep()}}">
                            <div class="form-group">
                                <label for="phone">Telefone <span class="text-danger">*</span></label>
                                <input type="text" name="telefone" id="telefone" class="form-control" required>
                            </div>
                            <button id="margin_button" type="submit" class="cart_total_button pull-right">Adicionar Novo Endereço</button>
                        </form>
                        <button class="button_clear cart_button col-md-12"><a href="/checkout">Voltar</a></button>
                        @endif
                    </div>

                    @else
                    <p class="col-md-12 alert alert-danger"><a href="{{ route('customer.address.create', [$customer->Id]) }}">Nenhuma endereço cadastrado, adicionar endereço.</a></p>
                    @endif
                </div>

            </div>

        </div>
        <div class="col-lg-6 card_checkout ">

            <div class="cart_details">
                <div class="checkout_title">Resumo do Pedido</div>
                <div class="cart_total">
                    <ul>
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title"><strong>Produto</strong></div>
                            <div class="cart_total_price ml-auto"><strong>Valor</strong></div>
                        </li>
                        @foreach($products as $product)
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title">{{$product->name}}</div>
                            @if(isset($product->couponPrice) && $descontoCondicional != null)
                            <div class="cart_total_price ml-auto">
                                <del class="text-danger">R${{number_format($product->price * $product->qty,2,',','.')}}</del>
                                R${{number_format($product->couponPrice * $product->qty,2,',','.')}}
                            </div>
                            @else
                            <div class="cart_total_price ml-auto">R${{number_format($product->price * $product->qty,2,',','.')}}</div>
                            @endif
                        </li>
                        @endforeach
                        @if(isset($shipping))
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title"><b>Frete</b></div>
                            <div id="shipping_cost" class="cart_total_price ml-auto"></div>
                        </li>
                        @endif
                        @if($descontoGeral === true)
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title">
                                <b>Desconto</b>
                            </div>
                            <div class="cart_total_price ml-auto">
                                @if($cupom->desconto)
                                <p class="text-success">- R$ {{number_format($cupom->desconto, 2, '.', ',') }}</p>
                                @elseif($cupom->descontopercentual)
                                <p class="text-success">-{{number_format($cupom->descontopercentual, 2, '.', ',') }} %</p>
                                @endif
                            </div>
                            <form method="get" action="{{route ('checkout.index')}}">
                                <button onclick="return confirm('Deseja Remover o Cupom?')" class="coupon_remove"><img src="images/trash.png" alt=""></button>
                            </form>
                        </li>
                        @elseif($descontoCondicional === true)
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title">
                                <b>Desconto</b>
                            </div>
                            <div class="cart_total_price ml-auto">
                                @if($cupom->desconto)
                                <p class="text-success">- R$ {{number_format($cupom->desconto, 2, '.', ',') }} em Produtos Selecionados</p>
                                @elseif($cupom->descontopercentual)
                                <p class="text-success">-{{number_format($cupom->descontopercentual, 2, '.', ',') }} % em Produtos Selecionados</p>
                                @endif
                            </div>
                            <form method="get" action="{{route ('checkout.index')}}">
                                <button onclick="return confirm('Deseja Remover o Cupom?')" class="coupon_remove"><img src="images/trash.png" alt=""></button>
                            </form>
                        </li>
                        @endif
                        <li class="d-flex flex-row align-items-start justify-content-start total_row">
                            <div class="cart_total_title">Total</div>
                            @if($descontoGeral != null || $descontoCondicional != null)
                            <div class="cart_total_price ml-auto">
                                <del class="text-danger" id="subtotal"></del>
                            </div>
                            <div id="total" class="cart_total_price ml-auto"></div>
                            @else
                            <div id="subtotal" class="cart_total_price ml-auto"></div>
                            @endif
                        </li>
                    </ul>
                </div>

            </div>
            <div class="cart_details h-100">
                <legend id="" class="checkout_title">Pagamento</legend>
                <div class="payment_options">
                    <div class="payment_option_row">
                        <input type="radio" id="radio_payment_cielo" name="payment_radio" value="cielo" class="regular_radio">
                        <label for="radio_payment_cielo">Cartão</label>
                        <div class="visa payment_option"><img src="images/visa.jpg" alt=""></div>
                        <div class="master payment_option"><img src="images/master.jpg" alt=""></div>
                    </div>

                    <div class="payment_option_row">
                        <input type="radio" id="radio_payment_pagseguro" name="payment_radio" value="boleto" class="regular_radio">
                        <label for="radio_payment_pagseguro">Boleto Bancário</label>
                        <div class="visa payment_option"><img src="images/boleto.png" alt=""></div>

                    </div>

                    <div class="payment_option_row">
                        <input type="radio" id="radio_payment_pagseguro" name="payment_radio" value="pix" class="regular_radio">
                        <label for="radio_payment_pagseguro">Pix</label>
                        <div class="visa payment_option"><img src="images/pix-bc-logo.png" style="height: 27px;" alt=""></div>

                    </div>

                    <form action="/checkout/pagamento" method="POST" class="mt-4 cielo_payment w-100">
                        {{ csrf_field() }}
                        <input type="hidden" name="type_payment" value="cartao">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="card_number">Numero do cartão <span class="text-danger">*</span></label>
                                    <input type="text" name="card_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="card_expiration">Validade do cartão <span class="text-danger">*</span></label>
                                    <input type="text" name="card_expiration" id="card_expiration" class="form-control">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="card_holder">Nome do Titular do cartão <span class="text-danger">*</span></label>
                                    <input type="text" name="card_holder" id="card_holder" class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="card_cvv">CVV <span class="text-danger">*</span></label>
                                    <input type="password" name="card_cvv" id="card_cvv" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="card_cvv">Número de parcelas <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Número de parcelas" name="installments" id="installments">
                                        <option selected>Selecione o número de parcelas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="cart_total_button mt-2">Realizar Pedido</button>

                    </form>
                    <form action="/checkout/boleto" method="POST" class="mt-4 boleto_payment w-100">
                        {{ csrf_field() }}
                        @if(isset($cupom->codigo))
                        <input type="hidden" name="cupom" value="{{$cupom->codigo}}">
                        @endif
                        @if(isset($shipping))
                        <input type="hidden" name="shipping" value="{{$shipping[42]->getValor()}}">
                        <button class="cart_total_button mt-2">
                            @else
                            <button class="cart_total_button mt-2" disabled>
                                @endif
                                <!-- <a href="/checkout/boleto" id="anchor" target="_blank"></a> -->Gerar Boleto
                            </button>
                    </form>
                    <form action="/checkout/pix" method="POST" class="mt-4 pix_payment w-100">
                        {{ csrf_field() }}
                        @if(isset($cupom->codigo))
                        <input type="hidden" name="cupom" value="{{$cupom->codigo}}">
                        @endif
                        @if(isset($shipping))
                        <input type="hidden" name="shipping" value="{{$shipping[42]->getValor()}}">
                        <button class="cart_total_button mt-2">
                            @else
                            <button class="cart_total_button mt-2" disabled>
                                @endif
                                <!-- <a href="/checkout/boleto" id="anchor" target="_blank"></a> -->Pagar com pix
                            </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

@else
<div class="row">
    <div class="col-md-12">
        <p class="alert alert-warning">Sem produtos no carrinho. <a href="{{ route('home') }}">Comprar agora!</a></p>
    </div>
</div>
@endif
@endsection
@section('js')
<script type="text/javascript">
    $(".cielo_payment").hide();
    $(".boleto_payment").hide();
    $(".pix_payment").hide();

    $('input[type=radio][name=payment_radio]').change(function() {
        switch ($(this).val()) {
            case "boleto":
                $(".pix_payment").hide();
                $(".cielo_payment").hide(function() {
                    $(".boleto_payment").show("slow");
                });
                break;
            case "cielo":
                $(".pix_payment").hide();
                $(".boleto_payment").hide(function() {
                    $(".cielo_payment").show("slow");
                });
                break;
            case "pix":
                $(".cielo_payment").hide();
                $(".boleto_payment").hide(function() {
                    $(".pix_payment").show("slow");
                });
                break;
            default:
                break;
        }
    });

    var formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });

    var radio = document.getElementsByName('price');

    if (radio[0]) {
        radio[0].addEventListener('click', getSedexPrice);
    }

    if (radio[1]) {
        radio[1].addEventListener('click', getPacPrice);
    }

    function hideShow() {
        document.getElementById('search').style.display = 'block';
        this.style.display = 'none';
        document.getElementById('shipping_options').style.display = 'none';
        document.getElementById('table').style.display = 'none';
        document.getElementById('margin_button').style.display = 'none';
        document.getElementById('cep_input').innerHTML =
            '<div class="autocomplete col-md-12"><form action="/checkout" method="GET" class="form">' +
            '<input type="text" name="cep" class="form-control col-md-12" placeholder="Procure um CEP">'
        '<button type="submit" id="search" class="search_button">' +
        '<img src="{{ asset("images/magnifying-glass.svg") }}" alt=""></button>' +
        '<button class="button_clear cart_button col-md-12"><a href="/checkout">Voltar</a></button>' +
        '</form></div> ';
    }

    var total = <?php echo $total ?>;
    var totalDesconto = <?php echo $totalDesconto ?>

    <?php
    if (isset($cupom) && $cupom->condicional != 1) :
    ?>
        var tipoCupom = 'geral';
        var descontoUnitario = <?php echo $cupom->desconto != null ? $cupom->desconto : 0 ?>;
        var descontoPercentual = <?php echo $cupom->descontopercentual != null ? $cupom->descontopercentual : 0 ?>;
    <?php
    else :
    ?>
        var tipoCupom = 'condicional';
        var descontoUnitario = 0;
        var descontoPercentual = 0;
    <?php endif ?>

    function getSedexPrice() {
        document.getElementById('shipping_cost').innerHTML = formatter.format(radio[0].value)
        var shipping = radio[0].value
        setSubTotalPrice(parseFloat(total) + parseFloat(shipping));
        if (tipoCupom == 'geral') {
            if (descontoUnitario > 0) {
                setTotalPrice(parseFloat(total) + parseFloat(shipping) - parseFloat(descontoUnitario));
            } else if (descontoPercentual > 0) {
                setTotalPrice(parseFloat(total) - (parseFloat(total) * (parseFloat(descontoPercentual) / 100)) + parseFloat(shipping));
            }
        } else if (tipoCupom == 'condicional') {
            setTotalPrice(parseFloat(total) - parseFloat(totalDesconto) + parseFloat(shipping));
        }
        if (total < 0) total = 0;
    }

    function getPacPrice() {
        document.getElementById('shipping_cost').innerHTML = formatter.format(radio[1].value)
        var shipping = radio[1].value;
        setSubTotalPrice(parseFloat(total) + parseFloat(shipping));
        if (tipoCupom = 'geral') {
            if (descontoUnitario > 0) {
                setTotalPrice(parseFloat(total) + parseFloat(shipping) - parseFloat(descontoUnitario));
            } else if (descontoPercentual > 0) {
                setTotalPrice(parseFloat(total) - (parseFloat(total) * (parseFloat(descontoPercentual) / 100)) + parseFloat(shipping));
            }
        } else if (tipoCupom = 'condicional') {
            setTotalPrice(parseFloat(total) + parseFloat(shipping) - parseFloat(totalDesconto));
        }
        if (total < 0) total = 0;
    }

    function setTotalPrice(total) {
        if (total < 0) {
            total = 0;
        }
        total = total.toFixed(2);
        $('#total').html(formatter.format(total));

        $('#installments option').remove();

        var optionPrimary = new Option(`Selecione o número de parcelas`, null);
        $(optionPrimary).attr({
            selected: true,
            disabled: true,
            hidden: true,
        })
        $("#installments").append(optionPrimary);

        for (let i = 1; i <= 12; i++) {
            var total_option = (total / i).toFixed(2)
            var option = new Option(`${i}x de ${formatter.format(total_option)} sem juros`, i);

            $("#installments").append(option)
        }
    }

    function setSubTotalPrice(subtotal) {
        if (subtotal < 0) {
            subtotal = 0;
        }
        subtotal = subtotal.toFixed(2);
        document.getElementById('subtotal').innerHTML = formatter.format(subtotal)
    }

    var button = document.getElementById('search')
    button.addEventListener('click', hideShow, false);

    /* function setTotal(total, shippingCost) {
         let computed = +shippingCost + parseFloat(total);
         $('#total').html(computed.toFixed(2));
     }

     function setShippingFee(cost) {
         el = '#shippingFee';
         $(el).html(cost);
         $('#shippingFeeC').val(cost);
     }

     function setCourierDetails(courierId) {
         $('.courier_id').val(courierId);
     }

     $(document).ready(function() {

         let clicked = false;

         $('#sameDeliveryAddress').on('change', function() {
             clicked = !clicked;
             if (clicked) {
                 $('#sameDeliveryAddressRow').show();
             } else {
                 $('#sameDeliveryAddressRow').hide();
             }
         });

         let billingAddress = 'input[name="billing_address"]';
         $(billingAddress).on('change', function() {
             let chosenAddressId = $(this).val();
             $('.address_id').val(chosenAddressId);
             $('.delivery_address_id').val(chosenAddressId);
         });

         let deliveryAddress = 'input[name="delivery_address"]';
         $(deliveryAddress).on('change', function() {
             let chosenDeliveryAddressId = $(this).val();
             $('.delivery_address_id').val(chosenDeliveryAddressId);
         });

         let courier = 'input[name="courier"]';
         $(courier).on('change', function() {
             let shippingCost = $(this).data('cost');
             let total = $('#total').data('total');

             setCourierDetails($(this).val());
             setShippingFee(shippingCost);
             setTotal(total, shippingCost);
         });

         if ($(courier).is(':checked')) {
             let shippingCost = $(courier + ':checked').data('cost');
             let courierId = $(courier + ':checked').val();
             let total = $('#total').data('total');

             setShippingFee(shippingCost);
             setCourierDetails(courierId);
             setTotal(total, shippingCost);
         }

     });*/
</script>
<script src="{{ asset('js/checkout_custom.js') }}" defer></script>
@endsection