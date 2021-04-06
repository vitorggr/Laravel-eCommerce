@if(!empty($products) && !collect($products)->isEmpty())
@foreach($products as $product)
<?php $directory = null ?>

<div class="owl-item">

    <a href="{{ route('front.get.product', str_slug($product->descricao,'-')) }}">

        @if(isset($product->images->first()->imgsalvar))
        <div class="product_image">
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