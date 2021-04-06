@extends('layouts.front.app')
@section('og')
<meta property="og:type" content="wishlist" />
<link rel="canonical" href="http://www.easyshop.com.br/favoritos" />
@endsection
@section('content')
<div class="home">
    <div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/promo_3.jpg" data-speed="0.8"></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="home_container">
                    <div class="home_content">
                        <div class="home_title">Favoritos</div>
                        <div class="breadcrumbs">
                            <ul>
                                <li><a> <i class="fa fa-home"></i> Home</a></li>
                                <li>Produtos Favoritados</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cart_container">
    <div class="container">

        @if(!$products->isEmpty())

        <div class="row">
            <div class="col">
                @include('layouts.errors-and-messages')
                <div class="cart_title">Sua Lista &nbsp;<i class="fa fa-cart-plus"></i></div>
            </div>
        </div>

        <div class="row products_container">
            @include('front.wishlist.product-wishlist')
        </div>

        <div class="row products_container">
            <div class="col">
                <div class="cart_control_bar d-flex flex-md-row flex-column align-items-start justify-content-start">
                    <form class="col-md-10" method="POST" action="favoritos/limpar">
                        {{ csrf_field() }}
                        <button onclick="return confirm('Deseja Limpar a Lista?')" class="button_clear cart_button">Limpar Lista</button>
                    </form>
                    <form method="POST" action="favoritos/adicionar">
                        {{ csrf_field() }}
                        <a class="ml-md-auto"><button class="button_update cart_button_2">Adicionar ao Carrinho</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="row">
        <div class="col-md-12">
            <p class="alert alert-warning">Nenhum produto favoritado. <a href="/">Adicionar agora!</a></p>
        </div>
    </div>
    @endif
</div>

@endsection
@section('css')
<style type="text/css">
    .product_info {
        float: left;
    }

    .product-description {
        padding: 10px 0;
    }

    .product-description p {
        line-height: 18px;
        font-size: 14px;
    }

    .product_content {
        margin-top: 7px;
    }

    .product_price {
        font-size: 24px;
        font-weight: 600;
        color: #8a8a8a;
        margin-top: 0px;
    }

    .rating {
        margin-top: 5px;
    }

    .product_buy {
        display: inline-block;
        background: #937c6f;
        vertical-align: middle;
        margin-right: 0px;
        -webkit-transition: all 200ms ease;
        -moz-transition: all 200ms ease;
        -ms-transition: all 200ms ease;
        -o-transition: all 200ms ease;
        transition: all 200ms ease;
    }

    .product_option {
        width: 37px;
        height: 37px;
        cursor: pointer;
    }

    .products_container {
        top: 60px;
    }

    .product_options {
        float: right;
        transform: translateY(11px);
    }

    .product_image img {
    max-width: 100%;
}
</style>
<link href="{{ asset('css/cart.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('js')
<script src="{{ asset('js/cart_custom.js') }}" defer></script>
<script src="{{ asset('js/easing.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('js/popper.js') }}" defer></script>
@endsection