@extends('layouts.front.app')
@section('og')
<meta property="og:type" content="pedido" />
<meta property="og:title" content="pedido" />
<meta property="og:description" content="confirmação de pedido" />
<link rel="canonical" href="http://www.easyshop.com.br/pedido" />
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{@asset('css/style.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/main_styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{@asset('css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/cart.css') }}">
@endsection
@section('content')
<section class="container content" id="top">
    <div class="row">
        <!-- Erros -->
        <div class="box-body">
            @include('layouts.errors-and-messages')
        </div>
        <!-- Titulo -->
        <div class="col-md-12">
            <h2> <i class="fa fa-cart-arrow-down"></i> Confirmação de pedido</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div>
                <!-- Tab panes -->
                <div class="tab-content customer-order-list">
                    <h1>#215265</h1>
                </div>
                <p class="subtitle">Detalhes</p>
                <table class="table">
                    <thead>
                        <th>Endereço</th>
                        <th>Forma de Pagamento</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <address>
                                    <strong>
                                        {{$addresses[3]->endereco}}
                                    </strong><br />
                                    {{$addresses[3]->endereco}} -
                                    @if(isset($addresses[1]))
                                    {{$addresses[1]->endereco}}
                                    @endif<br>
                                </address>
                            </td>
                            <td>
                            </td>
                            <td>
                                {{ config('cart.currency_symbol') }} {{$orders[0]->valortotal}}
                            </td>
                            <td>
                                <p class="text-center" style="color: #ffffff">{{ $orders[0]->data }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <p class="subtitle">Produtos</p>
                <table class="table">
                    <thead>
                        <th>Id</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                        <tr><a>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>

                                </td>
                                <td></td>
                            </a></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{ asset('js/product_custom.js') }}"></script>
@endsection