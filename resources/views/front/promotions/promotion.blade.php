@extends('layouts.front.app')
@section('css')
<link href="{{ asset('css/categories.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/categories_responsive.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('og')
<meta property="og:type" content="promotion" />
<meta property="og:title" content="{{ $promotion->descricao }}" />
<meta property="og:description" content="{{ $promotion->description }}" />
<link rel="canonical" href="http://www.easyshop.com.br/promoção" />
@endsection
@section('content')
<?php $directory = '../' ?>
<div class="home">
    <div class="home_background parallax-window" data-parallax="scroll" data-image-src="../images/categories.jpg" data-speed="0.8"></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="home_container">
                    <div class="home_content">
                        <div class="home_title">{{$promotion->descricao}}</div>
                        <div class="breadcrumbs">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li>Promoção</li>
                                <li>{{$promotion->descricao}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="products" class="products">






    <div class="container">

        <div id="sidebar_left" class="sidebar_left clearfix">
            <div class="container-sidebar">
                <div class="clear_price_btn"><a class="clear_filter" href="../promoção/{{$slug}}">Limpar Filtro</a></div>
                <div class="clear_price_btn close_filter">X</div>
                <!-- Price -->
                <div class="sidebar_section">
                    <div class="sidebar_title">Preço</div>
                    <div class="sidebar_section_content">
                        <div class="filter_price">
                            <div id="slider-range" class="slider-range"></div>
                            <p><input type="text" id="amount" class="amount" readonly style="border:0; font-weight:bold;"></p>
                            <!-- <div class="clear_price_btn">Limpar Filtro</div> -->
                        </div>
                    </div>
                </div>
                <!-- Color -->
                <div class="sidebar_section">
                    <div class="sidebar_title">Cor</div>
                    <div class="sidebar_section_content sidebar_color_content mCustomScrollbar" data-mcs-theme="minimal-dark">
                        <ul>
                            <!-- <li><a href="#"><span style="background:#a3ccff"></span>Azul</a></li>
                        <li><a href="#"><span style="background:#a3ffb2"></span>Verde</a></li>
                        <li><a href="#"><span style="background:#fdabf4"></span>Rosa</a></li>
                        <li><a href="#"><span style="background:#ecf863"></span>Amarelo</a></li>
                        <li><a href="#"><span style="background:#937c6f"></span>Marrom</a></li>
                        <li><a href="#"><span style="background:#000000"></span>Preto</a></li>
                        <li><a href="#"><span style="background:#ff5c00"></span>Laranja</a></li>
                        <li><a href="#"><span style="background:#a3ffb2"></span>Verde</a></li>
                        <li><a href="#"><span style="background:#f52832"></span>Vermelho</a></li> -->
                            @foreach($colors as $color)
                            @if(request()->input('tamanho')==null)
                            <li><a href="../promoção?promoção={{$slug}}&cor={{$color->descricao}}">
                                    <span style="background:{{$color->hexadecimal}}"></span>{{$color->descricao}}</a></li>
                            @else
                            <li><a href="../promoção?promoção={{$slug}}&cor={{$color->descricao}}&tamanho={{request()->input('tamanho')}}">
                                    <span style="background:{{$color->hexadecimal}}"></span>{{$color->descricao}}</a></li>
                            @endif
                            @endforeach

                        </ul>
                    </div>
                </div>

                <!-- Size -->
                <div class="sidebar_section">
                    <div class="sidebar_title">Tamanho</div>
                    <div class="sidebar_section_content">
                        <ul>
                            @if(request()->input('cor') != null)
                            <li><a href="../promoção?promoção={{$slug}}&cor={{request()->input('cor')}}&tamanho=PP">PP</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&cor={{request()->input('cor')}}&tamanho=P">P</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&cor={{request()->input('cor')}}&tamanho=M">M</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&cor={{request()->input('cor')}}&tamanho=G">G</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&cor={{request()->input('cor')}}&tamanho=GG">GG</a></li>
                            @else
                            <li><a href="../promoção?promoção={{$slug}}&tamanho=PP">PP</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&tamanho=P">P</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&tamanho=M">M</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&tamanho=G">G</a></li>
                            <li><a href="../promoção?promoção={{$slug}}&tamanho=GG">GG</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div id="size"></div>
            </div>
        </div>


        <div class="row">
            <hr>
            <div class="col-12">
                <div class="product_sorting clearfix">
                    <div class="view">
                        <div class="view_box box_view btn_filter"><i class="fa fa-filter"></i></div>
                        <div class="view_box box_view"><i class="fa fa-th-large" aria-hidden="true"></i></div>
                        <div class="view_box detail_view"><i class="fa fa-bars" aria-hidden="true"></i></div>
                    </div>
                    <div class="sorting">
                        <ul class="item_sorting">

                            <li>
                                <span class="sorting_text">Itens</span>
                                <span class="num_sorting_text">12</span>
                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                                <ul>
                                    <li class="num_sorting_btn"><span>3</span></li>
                                    <li class="num_sorting_btn"><span>6</span></li>
                                    <li class="num_sorting_btn"><span>12</span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row products_container">
            <div class="col">
                <div class="product_grid">
                    @include('front.products.product-list', ['products' => $products])
                </div>
            </div>
        </div>

        <div class="row page_num_container">
            <div class="col text-right">
                <ul class="page_nums">
                    <li>{{$products->links()}}</li>
                </ul>
            </div>
        </div>

    </div>

    <div class="sidebar_right clearfix">
    </div>


</div>
</div>

@include('front.newsletter.index')
@endsection

@section('js')
<script src="{{ asset(''.$directory.'js/isotope.pkgd.min.js') }}" type="text/javascript"></script>
<script src="{{ asset(''.$directory.'js/jquery.mCustomScrollbar.js') }}" type="text/javascript"></script>
<script src="{{ asset(''.$directory.'js/jquery-ui.js') }}" type="text/javascript"></script>
<script src="{{ asset(''.$directory.'js/categories_custom.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.star-rating-svg.js') }}"></script>
<script>
    $(".product").each(function() {
        $(".my-rating-6", this).starRating({
            totalStars: 5,
            emptyColor: 'lightgray',
            activeColor: '#f5c06f',
            initialRating: $('.rating', this).data("rating"),
            readOnly: true,
            strokeWidth: 0,
            useGradient: false,
            minRating: 0,
        });
    });
</script>
@endsection