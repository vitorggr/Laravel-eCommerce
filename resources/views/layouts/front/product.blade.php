<div class="product">
	<div class="container">
		<div class="row product_row">
			<div class="col-lg-7">




				<div class="product_image">

					@foreach($product->images as $image)
					@if($image->thumbnail)
					<div class="product_image_large">
						@if(isset($image->tags))
						<div class="tags-content">
							@foreach($image->tags as $tag)
							<a class="tag-product" href="{{ route('front.get.product', $tag->url) }}" style="{{$tag->posicao}}">{{$tag->titulo}}</a>
							@endforeach
						</div>
						@endif

						<figure class="product-cover-wrap">
							<img id="main-image" class="product-cover img-responsive" src="{{'../' . $image->imgsalvar }}" data-zoom="{{'../' . $image->imgsalvar }}">
						</figure>
					</div>

					@endif
					@endforeach

					<div class="product_image_thumbnails d-flex flex-row align-items-start justify-content-start">
						@foreach($product->images as $image)
						<div class="product_image_thumbnail" style="background-image:url({{'../' . $image->imgsalvar }})" data-image="{{ $image->imgsalvar }}" data-tags="{{$image->tags}}"></div>
						@endforeach
					</div>
				</div>




			</div>
			<!-- @include('layouts.errors-and-messages') -->
			<!-- Product Content -->
			<div class="col-lg-5">
				<div class="product_content">
					<div class="product_name">{{ $product->descricao }}</div>
					<div class="">
						@if(isset($product->grade->valorvenda))
							@if($product->promocao == true)
								<div class="cart_total_price text_promocao ml-auto">
									<del class="text-danger">R$ {{number_format($product->grade->valorvenda,2,',','.')}}</del>
								</div>
								<div class="cart_total_price price_product ml-auto product_price">R$ {{number_format($product->grade->valorvendadesconto,2,',','.')}}</div>
							@else
								<div class="cart_total_price price_product ml-auto product_price">R$ {{number_format($product->grade->valorvenda,2,',','.')}}</div>
							@endif
						@else
							<div class="cart_total_price price_product ml-auto product_price">R$ 00,00</div>
						@endif
					</div>

					<div class="rating my-rating-6" data-rating="{{$product->media}}"></div>

					@include('layouts.errors-and-messages')
					<form action="{{ route('carrinho.adicionar') }}" class="form-inline" method="post">
						{{ csrf_field() }}
						<!-- Product Quantity -->
						<div class="product_quantity_container">
							<span>Quantidade</span>
							<div class="product_quantity clearfix">
								<input type="hidden" name="product" value="{{ $product->id }}" />
								<input type="hidden" name="grade" value="{{ $product->grade }}" />

								<!--value="{{ old('quantity') }}"-->
								<input id="quantity_input" type="text" pattern="[0-9]*" name="quantity" value="1">
								<div class="quantity_buttons">
									<div id="quantity_inc_button" class="quantity_inc quantity_control"><i class="fa fa-caret-up" aria-hidden="true"></i></div>
									<div id="quantity_dec_button" class="quantity_dec quantity_control"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								</div>
							</div>
						</div>
						<!-- Product Size -->
						<div class="product_size_container">
							<span>Tamanho</span>
							@if($product->promocao == true)
							<input type="hidden" id="product_promo" value="true">
							@endif
							<div class="product_size">
								<ul class="d-flex flex-row align-items-start justify-content-start">
									@if($sizes->get('PP') != false)
									<li>
										@if($selectedSize=='PP' )
										<input type="radio" id="radio_1" value="PP" name="product_radio" class="regular_radio radio_1" data-price="{{$sizes->get('PP')['price']}}" data-price-promo="{{$sizes->get('PP')['price_promo']}}" checked>
										<label for="radio_1" id="selected_label">PP</label>
										@else
										<input type="radio" id="radio_1" value="PP" name="product_radio" class="regular_radio radio_1" data-price="{{$sizes->get('PP')['price']}}" data-price-promo="{{$sizes->get('PP')['price_promo']}}">
										<label for="radio_1" id="size_label">PP</label>
										@endif
									</li>
									@else
									<li>
										<input type="radio" id="radio_1" value="PP" name="product_radio" class="regular_radio radio_1" disabled>
										<label for="radio_1" class="label_disabled">PP</label>
									</li>
									@endif
									@if($sizes->get('P') != false)
									<li>
										@if($selectedSize=='P')
										<input type="radio" id="radio_2" value="P" name="product_radio" class="regular_radio radio_2" data-price="{{$sizes->get('P')['price']}}" data-price-promo="{{$sizes->get('P')['price_promo']}}" checked>
										<label for="radio_2" id="selected_label" id="selected_label">P</label>
										@else
										<input type="radio" id="radio_2" value="P" name="product_radio" class="regular_radio radio_2" data-price="{{$sizes->get('P')['price']}}" data-price-promo="{{$sizes->get('P')['price_promo']}}">
										<label for="radio_2" id="size_label" id="size_label">P</label>
										@endif

									</li>
									@else
									<li>
										<input type="radio" id="radio_2" value="P" name="product_radio" class="regular_radio radio_2" disabled>
										<label for="radio_2" class="label_disabled">P</label>
									</li>
									@endif
									@if($sizes->get('M') != false)
									<li>
										@if($selectedSize=='M')
										<input type="radio" id="radio_3" value="M" name="product_radio" class="regular_radio radio_3" data-price="{{$sizes->get('M')['price']}}" data-price-promo="{{$sizes->get('M')['price_promo']}}" checked>
										<label for="radio_3" id="selected_label">M</label>
										@else
										<input type="radio" id="radio_3" value="M" name="product_radio" class="regular_radio radio_3" data-price="{{$sizes->get('M')['price']}}" data-price-promo="{{$sizes->get('M')['price_promo']}}">
										<label for="radio_3" id="size_label">M</label>
										@endif

									</li>
									@else
									<li>
										<input type="radio" id="radio_3" value="M" name="product_radio" class="regular_radio radio_3" disabled>
										<label for="radio_3" class="label_disabled">M</label>
									</li>
									@endif
									@if($sizes->get('G') != false)
									<li>
										@if($selectedSize=='G')
										<input type="radio" id="radio_4" value="G" name="product_radio" class="regular_radio radio_4" data-price="{{$sizes->get('G')['price']}}" data-price-promo="{{$sizes->get('G')['price_promo']}}" checked>
										<label for="radio_4" id="selected_label">G</label>
										@else
										<input type="radio" id="radio_4" value="G" name="product_radio" class="regular_radio radio_4" data-price="{{$sizes->get('G')['price']}}" data-price-promo="{{$sizes->get('G')['price_promo']}}">
										<label for="radio_4" id="size_label">G</label>
										@endif
									</li>
									@else
									<li>
										<input type="radio" id="radio_4" value="G" name="product_radio" class="regular_radio radio_4" disabled>
										<label for="radio_4" class="label_disabled">G</label>
									</li>
									@endif
									@if($sizes->get('GG') != false)
									<li>
										@if($selectedSize=='GG')
										<input type="radio" id="radio_5" value="GG" name="product_radio" class="regular_radio radio_5" data-price="{{$sizes->get('GG')['price']}}" data-price-promo="{{$sizes->get('GG')['price_promo']}}" checked>
										<label for="radio_5" id="selected_label">GG</label>
										@else
										<input type="radio" id="radio_5" value="GG" name="product_radio" class="regular_radio radio_5" data-price="{{$sizes->get('GG')['price']}}" data-price-promo="{{$sizes->get('GG')['price_promo']}}">
										<label for="radio_5" id="size_label">GG</label>
										@endif
									</li>
									@else
									<li>
										<input type="radio" id="radio_5" value="GG" name="product_radio" class="regular_radio radio_5" disabled>
										<label for="radio_5" class="label_disabled">GG</label>
									</li>
									@endif
								</ul>
							</div>
							<button class="review_form_button" type="submit" style="margin-top: 50px;">ADICIONAR</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Reviews -->

		@if(isset($reviews))
		<div class="row">
			<div class="col">
				<div class="reviews">
					<div class="reviews_title">reviews</div>
					@foreach($reviews as $review)
					<div class="reviews_container">
						<ul>
							<!-- Review -->
							<li class=" review clearfix">
								<!-- <div class="review_image"><img src="../images/review_1.jpg" alt=""></div> -->
								<div>
									<!-- adicionar class="review_content" quando a imagem trazida da conta for setada -->
									<div class="review_name"><a href="#">{{$review->nome}}</a></div>
									<div class="review_date">{{$review->data}}</div>
									<div class="rating rating_{{$review->avaliacao}} review_rating" data-rating="4">
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
									</div>
									<div class="review_text">
										<p>{{$review->mensagem}}</p>
									</div>
								</div>
							</li>
						</ul>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endif

		<div class="row">
			<div class="col">
				<div class="review_form_container">
					<div class="review_form_title">Escreva um Review do Produto</div>
					<div class="review_form_content clearfix">
						<form action="{{route('product.review')}}" id="review_form" method="POST" class="review_form">
							{{ csrf_field() }}
							<input name="idproduto" type="hidden" value="{{$product->id}}">
							<div class="stars">
								<input class="star star-5" id="star-5" value="5" type="radio" name="star" />
								<label class="star star-5" for="star-5"></label>
								<input class="star star-4" id="star-4" value="4" type="radio" name="star" />
								<label class="star star-4" for="star-4"></label>
								<input class="star star-3" id="star-3" value="3" type="radio" name="star" />
								<label class="star star-3" for="star-3"></label>
								<input class="star star-2" id="star-2" value="2" type="radio" name="star" />
								<label class="star star-2" for="star-2"></label>
								<input class="star star-1" id="star-1" value="1" type="radio" name="star" />
								<label class="star star-1" for="star-1"></label>
							</div>
							<div class="d-flex flex-md-row flex-column align-items-start justify-content-between">
								<input type="text" class="review_form_input" name="subject" placeholder="Titulo">
							</div>
							<textarea class="review_form_text" name="msg" placeholder="Mensagem"></textarea>
							<button type="submit" class="review_form_button update_button" style="float: right">Enviar</button>
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script>
	var formatter = new Intl.NumberFormat('pt-BR', {
		style: 'currency',
		currency: 'BRL',
	});

	$(".product_size label").click(function() {
		var id = $(this).attr('for')
		var price_promo = $("#" + id).data("price-promo")
		var price = $("#" + id).data("price")

		if (price == null) {
			return;
		}

		$(".product_size label").attr('id', 'size_label')
		$(this).attr('id', 'selected_label')

		$(".text_promocao").hide()

		if ($("#product_promo").val()) {
			$(".text_promocao").html(`<del class="text-danger">${formatter.format(price_promo)}</del>`)
			$(".text_promocao").show()
		}

		$(".price_product").html(formatter.format(price))
	})
</script>