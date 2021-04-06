@extends('layouts.front.app')
@section('css')
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/main_styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart_responsive.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/cart.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('og')
<meta property="og:type" content="account" />
<link rel="canonical" href="http://www.easyshop.com.br/favoritos" />
@endsection
@section('content')
<!-- Main content -->
<?php

use App\Shop\Products\Product;
use Illuminate\Support\Facades\DB;

setlocale(LC_TIME, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');
?>
<section class="container content" id="top">
    <div class="row">
        <!-- Erros -->
        <div class="box-body">
            @include('layouts.errors-and-messages')
        </div>
        <!-- Titulo -->
        <div class="col-md-12">
            <h2> <i class="fa fa-home"></i> Minha Conta</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div>
                <!-- Abas -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" @if(request()->input('tab') == 'perfil') @endif><a href="#perfil" aria-controls="perfil" role="tab" data-toggle="tab">Perfil</a></li>
                    <li role="presentation" @if(request()->input('tab') == 'pedidos') @endif><a href="#pedidos" aria-controls="pedidos" role="tab" data-toggle="tab">Pedidos</a></li>
                    <li role="presentation" @if(request()->input('tab') == 'address') @endif><a href="#endereco" aria-controls="endereco" role="tab" data-toggle="tab">Endereços</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content customer-order-list">
                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'perfil')active @endif" id="perfil">
                        <!-- {{$customer->Nome}} <br /><small>{{$customer->Email}}</small> -->
                        <form action="{{ route('accounts.update') }}" method="post">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{$customer->Id}}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_number">Nome<span class="text-danger">*</span></label>
                                        <input type="text" name="Nome" class="form-control" value="{{$customer->Nome}}" placeholder="Digite seu nome">
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="card_expiration">Data de nascimento<span class="text-danger">*</span></label>
                                        <input type="date" name="aniversario" class="form-control" value="{{date('Y-m-d', strtotime($customer->aniversario)) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="card_expiration">CPF/CNPJ<span class="text-danger">*</span></label>
                                        <input type="text" name="Documento" class="form-control" value="{{$customer->Documento}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_expiration">E-mail <span class="text-danger">*</span></label>
                                        <input type="text" name="Email" id="card_expiration" class="form-control" value="{{$customer->Email}}" placeholder="Digite seu melhor e-mail">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_expiration">Confirme o e-mail <span class="text-danger">*</span></label>
                                        <input type="text" id="card_expiration" name="chekout_email" class="form-control" placeholder="Digite novamente seu novo e-mail" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_expiration">Senha <span class="text-danger">*</span></label>
                                        <input type="password" name="senha" id="card_expiration" class="form-control" placeholder="Digite sua nova senha" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_expiration">Confirme sua nova Senha <span class="text-danger">*</span></label>
                                        <input type="password" id="card_expiration" name="chekout_senha" class="form-control" placeholder="Digite novamente sua nova senha" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <button class="cart_total_button mt-2">Atualizar dados</button>
                        </form>


                    </div>
                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'pedidos')active @endif" id="pedidos">
                        @if(!$orders->isEmpty())
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Número Pedido</th>
                                    <th>Data de Realização</th>
                                    <th>Forma de Pagamento</th>
                                    <th>Valor Total</th>
                                    <th>Detalhamento </th>
                                    <th>Rastreio</th>
                                </tr>
                            </tbody>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    
                                    <td>
                                        {{date('d/m/Y', strtotime($order->data))}}
                                    </td>
                                    <td>{{$order->formaPagamento->Descricao}}</td>
                                    <td><span class="label @if($order['total'] != $order['total_paid']) label-danger @else label-success @endif">R$ {{number_format($order->valortotal,2,',','.')}}</span></td>
                                    <td> <a data-toggle="modal" data-target="#order_modal_{{$order['id']}}" title="Show order" href="javascript: void(0)">Consultar Pedido</a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="order_modal_{{$order['id']}}" tabindex="-1" role="dialog" aria-labelledby="MyOrders">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table">
                                                            <p class="subtitle text-center"></p>
                                                            <thead>
                                                                <th>Endereço</th>
                                                                <th>Valor Total</th>
                                                                <th>Forma de Pagamento</th>
                                                                <th>Status</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <address>
                                                                            {{$addresses->first()->endereco}} - {{$addresses->first()->complemento}}
                                                                        </address>
                                                                    </td>
                                                                    <td>R${{number_format($order->valortotal,2,',','.')}}</td>
                                                                    @if(!empty($order->boleto))
                                                                    <td><a href="{{$order->boleto->urlboleto}}" target="_blank">{{ $order->formaPagamento->Descricao }}</a></td>
                                                                    @elseif(!empty($order->pix))
                                                                    <td>
                                                                        <a  data-toggle="modal" data-target="#pixModal" href="javascript: void(0)" >{{$order->formaPagamento->Descricao}}</a>
                                                                       

                                                                        <!-- Modal -->
                                                                        <div class="modal fade" id="pixModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content pix">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img src="{{$order->pix->qrcode}}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    @else
                                                                    <td>{{ $order->formaPagamento->Descricao }}</td>
                                                                    @endif
                                                                    <td>{{$order->history}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <hr>
                                                        <p class="subtitle text-center">Produtos</p>
                                                        <table class="table">
                                                            <thead>
                                                                <th>Descrição</th>
                                                                <th>Quantidade</th>
                                                                <th>Total</th>
                                                                <th></th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($order->orderItem as $item)
                                                                <tr>
                                                                    <a>
                                                                        <td><?php
                                                                            echo Product::where('id', $item->idproduto)->first()->descricao;
                                                                            ?></td>
                                                                        <td>{{intval($item->qtd)}}</td>
                                                                        <td>R${{number_format($item->valor,2,',','.')}}</td>
                                                                        <td><img src="
                                                                        <?php
                                                                        echo DB::table('tblprodutofoto')->where('idproduto', $item->idproduto)->first()->imgsalvar;
                                                                        ?>" width=50px height=50px></td>
                                                                    </a>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <!-- <p class="text-center"></p> -->
                                        <a data-toggle="modal" data-target="#order_rastreio_{{$order['id']}}" title="Show order" href="javascript: void(0)">Rastrear Pedido</a>
                                        <!-- Button trigger modal -->
                                        <!-- Modal -->
                                        <div class="modal fade" id="order_rastreio_{{$order['id']}}" tabindex="-1" role="dialog" aria-labelledby="MyOrders">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row d-flex justify-content-between px-3 top">
                                                            <div class="d-flex">
                                                                <h5>Pedido <span class="text-primary font-weight-bold">#Y34XDHR</span></h5>
                                                            </div>
                                                            <div class="d-flex flex-column text-sm-right">
                                                                <p class="mb-0">Chegada Estimada <span>
                                                                        29/03/2021
                                                                    </span></p>
                                                                <p>Codigo de rastreio <span class="font-weight-bold">234094567242423422898</span></p>
                                                            </div>
                                                        </div> <!-- Add class 'active' to progress -->
                                                        <div class="row d-flex justify-content-center text-center">
                                                            <div class="col-12">
                                                                <p>Ultima Atualização <span class="font-weight-bold">{{$order->history}}</span></p>
                                                                <ul id="progressbar" class="text-center">
                                                                    <!-- adicionar 'active' apos conclusao de etapa -->
                                                                    <li class="active step0"></li>
                                                                    <li class="step0"></li>
                                                                    <li class="step0"></li>
                                                                    <li class="step0"></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-between top">
                                                            <div class="row d-flex icon-content">
                                                                <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                                                                <div class="d-flex flex-column">
                                                                    <p class="font-weight-bold">Pedido<br>Feito</p>
                                                                </div>
                                                            </div>
                                                            <div class="row d-flex icon-content"> <img class="icon" src="https://i.imgur.com/u1AzR7w.png">
                                                                <div class="d-flex flex-column">
                                                                    <p class="font-weight-bold">Pedido<br>Separado</p>
                                                                </div>
                                                            </div>
                                                            <div class="row d-flex icon-content"> <img class="icon" src="https://i.imgur.com/TkPm63y.png">
                                                                <div class="d-flex flex-column">
                                                                    <p class="font-weight-bold">Pedido<br>a Caminho</p>
                                                                </div>
                                                            </div>
                                                            <div class="row d-flex icon-content"> <img class="icon" src="https://i.imgur.com/HdsziHP.png">
                                                                <div class="d-flex flex-column">
                                                                    <p class="font-weight-bold">Pedido<br>Concluído</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>


                                  
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="alert alert-warning">Nenhum pedido encontrado. <a href="{{ route('home') }}">Comprar agora!</a></p>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane @if(request()->input('tab') == 'endereco')active @endif" id="endereco">
                        @if(!$addresses->isEmpty())
                        <table class="table">
                            <thead>
                                <th scope="col">Tipo</th>
                                <th scope="col">Endereço</th>
                                <th scope="col">Complemento</th>
                                <th scope="col">Cidade</th>
                                <th scope="col">Estado</th>
                                <th scope="col">CEP</th>
                                <th scope="col">Telefone</th>
                                <th scope="col"></th>
                                <th scope="col"></th>

                            </thead>
                            <tbody>
                                @foreach($addresses as $address)
                                <tr>
                                    <td>{{$address->tipo}}</td>
                                    <td>{{$address->endereco}}</td>
                                    <td>{{$address->complemento}}</td>
                                    <td>{{$address->cidade}}</td>
                                    <td>{{$address->estado}}</td>
                                    <td>{{$address->cep}}</td>
                                    <td>{{$address->telefone}}</td>
                                    <td>
                                        <form method="post" action="{{ route('customer.address.destroy', [\Auth::user()->Id, $address->id]) }}" class="form-horizontal">
                                            <div class="btn-group">
                                                <input type="hidden" name="_method" value="delete">
                                                {{ csrf_field() }}
                                                <a href="{{ route('customer.address.edit', [\Auth::user()->Id, $address->id]) }}" class="cart_total_button_2"> <i class="fa fa-pencil"></i> Editar &NonBreakingSpace;</a>
                                    <td> <button onclick="return confirm('Are you sure?')" type="submit" class=""> <i class="fa fa-trash"></i> Deletar</button></td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('customer.address.create', \Auth::user()->Id) }}" class="cart_total_button mt-2 text-center">Adicionar um Endereço</a>
                        @else
                        <br />
                        <p class="alert alert-warning">Nenhum endereço encontrado</p>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('customer.address.create', \Auth::user()->Id) }}" class="button_update cart_button_2">Adicionar um Endereço</a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script type="text/javascript">
    var options = {
        onKeyPress: function(cpf, ev, el, op) {
            var masks = ['000.000.000-000', '00.000.000/0000-00'];
            $("input[name='Documento']").mask((cpf.length > 14) ? masks[1] : masks[0], op);
        }
    }
    console.log(options);
    $("input[name='Documento']").length > 11 ? $("input[name='Documento']").mask('00.000.000/0000-00', options) : $("input[name='Documento']").mask('000.000.000-00#', options);
</script>
@endsection