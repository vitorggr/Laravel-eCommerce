@extends('layouts.front.app')
@section('og')
<meta property="og:type" content="cart" />
<link rel="canonical" href="http://www.easyshop.com.br/carrinho" />
@endsection
@section('content')
<div class="home">
    <div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/cart.jpg" data-speed="0.8"></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="home_container">
                    <div class="home_content">
                        <div class="home_title">Shopping Cart</div>
                        <div class="breadcrumbs">
                            <ul>
                                <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Home</a></li>
                                <li>Carrinho</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cart_container">
    <div class="container"><br><br>
        @if(!$cartItems->isEmpty())
        <div class="row">
            <div class="col">
                @include('layouts.errors-and-messages')
                <div class="cart_title">seu carrinho &nbsp;<i class="fa fa-cart-plus"></i></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="cart_bar d-flex flex-row align-items-center justify-content-start">
                    <div class="cart_bar_title_name">Produto</div>
                    <div class="cart_bar_title_content ml-auto">
                        <div class="cart_bar_title_content_inner d-flex flex-row align-items-center justify-content-end">
                            <div class="cart_bar_title_price">Preço</div>
                            <div class="cart_bar_title_price">Tamanho</div>
                            <div class="cart_bar_title_quantity">Quantidade</div>
                            <div class="cart_bar_title_total">Total</div>
                            <div class="cart_bar_title_button"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="cart_products">
                    <ul>
                        @foreach($cartItems as $cartItem)
                        <li class=" cart_product d-flex flex-md-row flex-column align-items-md-center align-items-start justify-content-start">
                            <!-- Product Image -->
                            <div class="cart_product_image">
                                <a href="{{$cartItem->name}}" class="hover-border">
                                    @if(isset($cartItem->product->images->first()->imgsalvar))
                                    <img class="cart_image" src="{{$cartItem->product->images->first()->imgsalvar}}" alt="{{ $cartItem->name }}">
                                    @else
                                    <img src="https://placehold.it/120x120" alt="" class="img-responsive img-thumbnail">
                                    @endif
                                </a>
                            </div>
                            <div class="cart_product_name">{{ $cartItem->name }}</div>
                            <div class="cart_product_info ml-auto">
                                <div class="cart_product_info_inner d-flex flex-row align-items-center justify-content-md-end justify-content-start">
                                    <!-- Product Price -->
                                    <div class="cart_product_price">R$ {{ number_format($cartItem->price, 2) }}</div>
                                    <!-- Product Price -->
                                    <div class="cart_product_price">{{$cartItem->tamanho}}</div>
                                    <!-- Product Quantity -->
                                    <div class="product_quantity_container">

                                        <form action="{{ route('carrinho.update', $cartItem->rowId) }}" class="form-inline" method="post">
                                            <div class="product_quantity clearfix">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="put">
                                                <input id="quantity_input" type="text" name="quantity" value="{{ $cartItem->qty }}">
                                                <div class="quantity_buttons">
                                                    <a class="fa fa-caret-up quantity_inc quantity_control" href="carrinho/mais/{{$cartItem->rowId}}/{{$cartItem->qty}}" aria-hidden="true"></a>
                                                    <a class="fa fa-caret-down quantity_dec quantity_control" href="carrinho/menos/{{$cartItem->rowId}}/{{$cartItem->qty}}" aria-hidden="true"></a>
                                                </div>
                                            </div>

                                    </div>
                                    <!-- Products Total Price -->
                                    <div class="cart_product_total">R$ {{ number_format(($cartItem->qty*$cartItem->price), 2) }}</div>
                                    <!-- Product Cart Trash Button -->
                                    <form action="{{ route('carrinho.destroy', $cartItem->rowId) }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="cart_product_button">
                                            <button onclick="return confirm('Deseja Remover Esse Item?')" class="cart_product_remove"><img src="images/trash.png" alt=""></button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </li>
                    </ul>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="cart_control_bar d-flex flex-md-row flex-column align-items-start justify-content-start">
                    <form action="carrinho/limpar" method="post">
                        {{ csrf_field() }}
                        <button onclick="return confirm('Deseja Limpar O Carrinho?')" class="button_clear cart_button">Limpar Carrinho</button>
                    </form>
                 
                    <a href="{{ route('home') }}" class="ml-md-auto"><button class="button_update cart_button_2">Continuar Comprando</button></a>
                </div>
            </div>
        </div>
        <div class="row cart_extra">
            <!-- Cart Coupon -->
            <!-- <div class="col-lg-6">
                <div class="cart_coupon">
                    <div class="cart_title">Cupom de desconto</div>
                    <form action="{{ route('carrinho.cupom')}}" method="POST" class="cart_coupon_form d-flex flex-row align-items-start justify-content-start mt-5" id="cart_coupon_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="ativo" value="1">
                        <input type="text" name="codigo" class="cart_coupon_input" placeholder="Adicione o código" required="required">
                        <button class="button_clear cart_button_2">aplicar </button>
                    </form>
                </div>
            </div> -->
            <div class="col-lg-5 offset-lg-1 ml-auto">
                <div class="cart_total">
                    <div class="cart_title">Valor Do Carrinho</div>
                    <ul>
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title">
                                <p>Subtotal</p>
                            </div>
                            <div class="cart_total_price ml-auto">
                                <p>R$ {{ number_format($subtotal, 2, '.', ',') }}</p>
                            </div>
                        </li>
                        @if($descontoAtivo === true)
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title">
                                <p>Desconto</p>
                            </div>
                            <div class="cart_total_price ml-auto">
                                <p class="text-success">- R$ {{number_format($cupom->desconto, 2, '.', ',') }}</p>
                            </div>
                            <form method="get" action="{{route ('carrinho.index')}}">
                                <button onclick="return confirm('Deseja Remover O Cupom?')" class="coupon_remove"><img src="images/trash.png" alt=""></button>
                            </form>
                        </li>
                        @endif
                        <!-- <li class="d-flex flex-row align-items-center justify-content-start">

                            <div class="cart_total_title">Frete</div>
                            @if(isset($shippingFee) && $shippingFee != 0)
                            <div class="cart_total_price ml-auto">R$ {{ $shippingFee }}</div>
                            @endif
                        </li> -->
                        <li class="d-flex flex-row align-items-center justify-content-start">
                            <div class="cart_total_title">
                                <p>Total</p>
                            </div>
                            @if($descontoAtivo === true)
                            <div class="cart_total_price ml-auto">
                                <p><del class="text-danger">
                                        @if($total < $cupom->desconto )
                                            R$ {{ number_format($subtotal, 2, '.', ',') }}</del></p>
                                @else
                                R$ {{ number_format($total+$cupom->desconto, 2, '.', ',') }}</del></p>
                                @endif
                            </div>
                            <div class="cart_total_price ml-auto">
                                <p>R$ {{ number_format($total, 2, '.', ',') }}</p>
                            </div>
                            @else
                            <div class="cart_total_price ml-auto">
                                <p>R$ {{ number_format($total, 2, '.', ',') }}</p>
                            </div>
                            @endif
                        </li>

                    </ul>
                    @if(isset($cupom->codigo))
                    <a href="checkout?cupom={{$cupom->codigo}}"><button class="cart_total_button">prosseguir para pagamento</button></a>
                    @else
                    <a href="checkout"><button class="cart_total_button">prosseguir para pagamento</button></a>
                    @endif
                </div>
            </div>

        </div>
        <!-- Newsletter -->
        @else
        <div class="row">
            <div class="col-md-12">
                <p class="alert alert-warning">Nenhum produto no carrinho de compras. <a href="{{ route('home') }}">Comprar agora!</a></p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
@section('css')
<style type="text/css">
    .product-description {
        padding: 10px 0;
    }

    .product-description p {
        line-height: 18px;
        font-size: 14px;
    }
</style>
<link href="{{ asset('css/cart.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart_responsive.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('js')
<script src="{{ asset('js/cart_custom.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
@endsection