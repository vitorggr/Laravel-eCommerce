@extends('layouts.front.app')
@section('css')
<link href="{{ asset('css/categories.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/categories_responsive.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/style.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/main_styles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('og')
<meta property="og:type" content="colecoes" />
<meta property="og:title" content="colecoas" />
<meta property="og:description" content="colecoes" />
<link rel="canonical" href="http://www.easyshop.com.br/colecoes" />
@endsection
@section('content')
<div class="row justify-content-center">

    <div class="extra clearfix col-lg-10">
        @foreach($collections as $collection)
        @if($loop->index % 2 != 0)
        <div class="extra_promo extra_promo_1">
            <div class="extra_promo_image" style="background-image: url({{ asset('../images/extra_1.jpg') }});"></div>
            <div class="extra_1_content d-flex flex-column align-items-center justify-content-center text-center">
                <div class="extra_1_price">30%<span>off</span></div>
                <div class="extra_1_title">On all shoes</div>
                <div class="extra_1_text">*Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra.</div>
                <div class="button extra_1_button"><a href="checkout.html">check out</a></div>
            </div>
        </div>
        @else
        <div class="extra_promo extra_promo_2">
            <div class="extra_promo_image" style="background-image:url({{ asset('../images/extra_1.jpg') }});"></div>
            <div class="extra_2_content d-flex flex-column align-items-center justify-content-center text-center">
                <div class="extra_2_title">
                    <div class="extra_2_center">&</div>
                    <div class="extra_2_top">Mix</div>
                    <div class="extra_2_bottom">Match</div>
                </div>
                <div class="extra_2_text">*Integer ut imperdiet erat. Quisque ultricies lectus tellus, eu tristique magna pharetra.</div>
                <div class="button extra_2_button"><a href="checkout.html">check out</a></div>
            </div>
        </div>
        @endif
        @endforeach

</div>
@endsection
@section('js')
<script src="{{ asset('js/categories_custom.js') }}" type="text/javascript"></script>
@endsection