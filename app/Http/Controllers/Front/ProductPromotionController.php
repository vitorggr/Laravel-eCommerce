<?php

namespace App\Http\Controllers\Front;

use App\Helpers\PaginateCollectionHelper;
use App\Http\Controllers\Controller;
use App\Shop\Products\Color;
use App\Shop\Products\Product;
use App\Shop\Products\ProductPromotion;
use App\Shop\Promotions\Promotion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProductPromotionController extends Controller
{


    public function getPromotionList(string $slug)
    {
        $products = new Collection();
        $promotion = Promotion::where('descricao',str_replace('-',' ',$slug))->first();
        $productPromotion = ProductPromotion::where('idpromocao',$promotion->id)->get();

        foreach ($productPromotion as $product) {
            $products->push(Product::where('id',$product->idproduto)->first());
        }
        
        //Adiciona grade à lista de produtos
        $products = $this->getGrade($products);
        //adiciona cores à lista de produtos
        //$products = $this->getColors($products);
        //adiciona valor promocional
        $products->map(function($product){
            $this->checkPromotion($product);
        });

        //Pagina o resultado
        $products = PaginateCollectionHelper::paginate($products, 12);

        return view('front.promotions.promotion', [
            //'colors' => array_filter($this->getColors($products)),
            'colors' => Color::all(),
            'slug' => str_slug($slug,'-'),
            'promotion' => $promotion,
            'products' => $products
        ]);
    } 

    //$title = str_slug("Laravel 5 Framework", "-");
}
