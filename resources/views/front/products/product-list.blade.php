@if(!empty($products) && !collect($products)->isEmpty())
@foreach($products as $product)
<?php $directory = null ?>

<div class="col-lg-4 col-md-12 product">
    @if(isset($product->grade->first()->descricao))
    <input type="hidden" value="{{$product->grade->first()->descricao}}">
    @endif
    <a href="{{ route('front.get.product', str_slug($product->descricao,'-')) }}">

        @if(isset($product->images->first()->imgsalvar))
        <div class="product_image">
            @if(isset($product->promocaoTitle))
            <!-- 
                            1 - diagonal (etiquete-promo-content-diagonal);
                            2 - minimalista (etiquete-promo-content-minimalista);
                            3 - circulo (etiquete-promo-content-circulo);
                            4 - bandeira (etiquete-promo-content-bandeira);
                            5 - horizontal (etiquete-promo-content-horizontal);
                        -->
            <div class="etiquete-promo-content-{{$product->promocaoEstilo}}">
                <span class="etiquete-promo">{{$product->promocaoTitle}}</span>
            </div>
            @endif
            @foreach($product->images as $image)
            @if($image->thumbnail)
            <img src="{{'../' . $image->imgsalvar }}" alt="{{ $product->descricao }}">
            @endif
            @endforeach

        </div>
        @else
        <img src="https://placehold.it/350x538" alt="{{ $product->descricao }}" />
        @endif

        <a href="{{ route('front.get.product', str_slug($product->descricao,'-')) }}">
        <div class="rating my-rating-6" data-rating="{{$product->media}}"></div>

        <div class="product_content clearfix">
            <div class="product_info">
                <div class="product_name">{{ mb_strimwidth($product->descricao, 0, 25, "...") }}</div>
                <div class="product_price">
                    @if(isset($product->grade->valorvenda))
                    @if($product->promocao == true)
                    <div class="cart_total_price ml-auto">
                        <del class="text-danger">R${{number_format($product->grade->valorvenda,2,',','.')}}</del>
                    </div>
                    <div class="cart_total_price ml-auto product_price">R${{number_format($product->grade->valorvendadesconto,2,',','.')}}</div>
                    @else
                    <div class="cart_total_price ml-auto product_price">R${{number_format($product->grade->valorvenda,2,',','.')}}</div>
                    @endif
                    @else
                    <div class="cart_total_price ml-auto product_price">R$ 00,00</div>
                    @endif
                </div>
            </div>
            <div class="product_options">
                <form action="{{ route('favoritos.store') }}" class="" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="quantity" value="1" />
                    <input type="hidden" name="product" value="{{ $product->id }}">
                    <input type="hidden" name="grade" value="{{ $product->grade }}">
                    <button role="button" id="add-to-cart-btn" type="submit" class="product_fav product_option">
                        <!-- <img class="star_btn" src="{{ asset(''.$directory.'images/star2.svg') }}" alt=""> -->

                        <?php
                        $items = session()->get('wishlist');
                        if (!empty($items)) {
                            if ($items['default']->firstWhere('descricao',  $product->descricao)) {
                        ?>
                                <img class="star_btn" src="{{ asset(''.$directory.'images/star2.svg') }}" alt="">
                            <?php
                            } else {
                            ?>
                                <img class="star_btn" src="{{ asset(''.$directory.'images/star.svg') }}" alt="">
                            <?php
                            };
                        } else {
                            ?>
                            <img class="star_btn" src="{{ asset(''.$directory.'images/star.svg') }}" alt="">
                        <?php
                        };
                        ?>
                    </button>
                </form>
            </div>
            <div class="product_options">
                <form action="../carrinho/adicionar" class="" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="grade" value="{{$product->grade}}" />
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="product" value="{{ $product->id }}">
                    <button role="button" id="add-to-cart-btn star" type="submit" class="product_buy product_option">
                        <img src="{{ asset(''.$directory.'images/shopping-bag-white.svg') }}" alt=""></button>
                </form>
            </div>
        </div>

    </a>
</div>
<!-- Modal -->
@endforeach

@if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
<div class="row">
    <div class="col-md-12">
        <div class="pull-left">
        </div>
    </div>
</div>
@endif
@else
<p class="alert alert-warning col-md-12">Sem Produtos Registrados Até o Momento</p>
@endif