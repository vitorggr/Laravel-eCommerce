@extends('layouts.front.app')

@section('og')
<meta property="og:type" content="product" />
<meta property="og:title" content="{{ $product->descricao }}" />
<meta property="og:description" content="{{ strip_tags($product->descricao) }}" />
<link rel="canonical" href="http://www.easyshop.com.br/produto" />
@if(!is_null($product->cover))
<meta property="og:image" content="{{ asset("storage/$product->cover") }}" />
@endif
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="css/star-rating-svg.css">
<link rel="stylesheet" type="text/css" href="css/product.css">
<link rel="stylesheet" type="text/css" href="css/product_responsive.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/style.min.css">
@endsection

@section('content')

<!-- Home -->

<div class="home">
	<div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/product.jpg" data-speed="0.8"></div>
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="home_container">
					<div class="home_content">
						<div class="home_title">{{$product->descricao}}</div>
						<div class="breadcrumbs">
							<ul>
								<li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Home</a></li>
								@if(isset($category))
								<li><a href="{{ route('front.category.slug', $category->id) }}"> {{ $category->descricao }}</a></li>
								@endif
								<li class="active"> {{$product->descricao}}</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('layouts.front.product')
@endsection

@section('js')
<script src="{{ asset('js/product_custom.js') }}"></script>
<script src="{{ asset('js/jquery.star-rating-svg.js') }}"></script>
<script>
	$(".my-rating-6").starRating({
		totalStars: 5,
		emptyColor: 'lightgray',
		activeColor: '#f5c06f',
		initialRating: $('.rating').data("rating"),
		readOnly: true,
		strokeWidth: 0,
		useGradient: false,
		minRating: 0,
	});
</script>
@endsection