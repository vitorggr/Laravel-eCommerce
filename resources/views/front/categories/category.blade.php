@extends('layouts.front.app')
@section('css')
<link href="{{ asset('css/categories.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/categories_responsive.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('og')
<meta property="og:type" content="category" />
<meta property="og:title" content="{{ $category->descricao }}" />
<meta property="og:description" content="{{ $category->description }}" />
<link rel="canonical" href="http://www.easyshop.com.br/categoria" />
@if(!is_null($category->cover))
<meta property="og:image" content="{{ asset("storage/$category->cover") }}" />
@endif
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
                        <div class="home_title">{{$category->descricao}}</div>
                        <div class="breadcrumbs">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li>Categorias</li>
                                <li>{{$category->descricao}}</li>
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
                <div class="clear_price_btn"><a class="clear_filter" href="../categoria/{{$slug}}">Limpar Filtro</a></div>
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
                            @foreach($colors as $color)
                            @if(request()->input('tamanho')==null)
                            <li><a href="../categoria?categoria={{$slug}}&cor={{str_slug($color->descricao,'-')}}">
                                    <span style="background:{{$color->hexadecimal}}"></span>{{str_slug($color->descricao,' ')}}</a></li>
                            @else
                            <li><a href="../categoria?categoria={{$slug}}&cor={{str_slug($color->descricao,'-')}}&tamanho={{request()->input('tamanho')}}">
                                    <span style="background:{{$color->hexadecimal}}"></span>{{str_slug($color->descricao,' ')}}</a></li>
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
                            <li><a href="../categoria?categoria={{$slug}}&cor={{request()->input('cor')}}&tamanho=PP">PP</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&cor={{request()->input('cor')}}&tamanho=P">P</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&cor={{request()->input('cor')}}&tamanho=M">M</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&cor={{request()->input('cor')}}&tamanho=G">G</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&cor={{request()->input('cor')}}&tamanho=GG">GG</a></li>
                            @else
                            <li><a href="../categoria?categoria={{$slug}}&tamanho=PP">PP</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&tamanho=P">P</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&tamanho=M">M</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&tamanho=G">G</a></li>
                            <li><a href="../categoria?categoria={{$slug}}&tamanho=GG">GG</a></li>
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

        @if(isset($cards[0]))
        <div class="sidebar_promo_1 sidebar_promo d-flex flex-column align-items-center justify-content-center">
            <div class="sidebar_promo_image" style="background-image: url(../{{$cards[0]->bannerlateral}})"></div>
            <div class="sidebar_promo_content text-center">
                <div class="sidebar_promo_title">{{$cards[0]->titulo}}<span>
                        <!-- off -->
                    </span></div>
                <div class="sidebar_promo_subtitle">{{$cards[0]->subtitulo}}</div>
                <div class="sidebar_promo_button"><a href="../promoção/{{str_slug($cards[0]->descricao,'-')}}">Comprar</a></div>
            </div>
        </div>

        @if(isset($cards[1]))
        <div class="sidebar_promo_2 sidebar_promo">
            <div class="sidebar_promo_image" style="background-image: url(../{{$cards[1]->bannerlateral}})"></div>
            <div class="sidebar_promo_content text-center">
                <div class="sidebar_promo_title">{{$cards[1]->titulo}}<span>
                        <!-- off -->
                    </span></div>
                <div class="sidebar_promo_subtitle">{{$cards[1]->subtitulo}}</div>
                <div class="sidebar_promo_button"><a href="../promoção/{{str_slug($cards[1]->descricao,'-')}}">Comprar</a></div>
            </div>
        </div>
        @endif
        @endif

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