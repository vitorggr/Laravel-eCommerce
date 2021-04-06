@if(!empty($products) && !collect($products)->isEmpty())
@foreach($products as $product)
<?php $directory = null ?>

<div class="col-lg-4 col-md-12  product">
    @if(isset($product->grade->descricao))
    <input type="hidden" value="{{$product->grade->descricao}}">
    @endif
    <a href="{{ route('front.get.product', $product->descricao) }}">

        @if(isset($product->cover))
        <div class="product_image">
            <img src="{{ asset(''.$product->cover)}}" alt="{{ $product->descricao }}">
        </div>
        @else
        <img src="https://placehold.it/350x538" alt="{{ $product->descricao }}" />
        @endif

        <div class="rating rating_4">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>

        <div class="product_content clearfix">
            <div class="product_info">
                <div class="product_name">{{ mb_strimwidth($product->descricao, 0, 25, "...") }}

                </div>
                <div class="product_price">
                    @if(isset($product->price))
                        R${{$product->price}}
                        R${{$product->valorvenda}}
                    @else
                        R$ 0,00
                    @endif
                </div>
            </div>
            <div class="product_options">
                <form action="{{ route('favoritos.destroy', $product->rowId) }}" class="" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="delete">
                    <button role="button" id="add-to-cart-btn" type="submit" class="product_fav product_option">
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
                <form action="carrinho/adicionar" class="" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="grade" value="{{$product->grade}}" />
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="product" value="{{ $product->id }}">
                    <button role="button" id="add-to-cart-btn star" type="submit" class="product_buy product_option">
                        <img src="{{ asset(''.$directory.'images/shopping-bag-white.svg') }}" alt=""></button>
                </form>
            </div>



        </div>

</div>
</a>
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