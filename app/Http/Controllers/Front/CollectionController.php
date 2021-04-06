<?php

namespace App\Http\Controllers\Front;

use App\Helpers\PaginateCollectionHelper;
use App\Http\Controllers\Controller;
use App\Shop\Products\ProductCollection;
use App\Shop\Collection\Collection as CollectionModel;
use App\Shop\Products\Color;
use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class CollectionController extends Controller
{
    public function getCollection(string $slug)
    {
        $products = new Collection();
        $collection = CollectionModel::where('nome', str_replace('-', ' ', $slug))->first();
        //->where('datafim','<',date("Y-m-d"))
        
        $productCollection = ProductCollection::where('idcolecao', $collection->id)->get();
        foreach ($productCollection as $product) {
            $products->push(Product::where('id', $product->idproduto)->first());
        }

        //Adiciona grade à lista de produtos
        $products = $this->getGrade($products);

        //adiciona cores à lista de produtos
        //$products = $this->getColors($products);

        //Busca se existem Preços Promocionais
        $products->map(function ($product) {
            $this->checkPromotion($product);
            //Busca media da avaliação do produto
            $product->media = $this->getRating($this->getReviews($product));
        });

        //Pagina o resultado
        $products = PaginateCollectionHelper::paginate($products, 12);

        return view('front.collections.collection', [
            //'colors' => array_filter($this->getColors($products)),
            'colors' => Color::all(),
            'slug' => str_slug($slug, '-'),
            'collection' => $collection,
            'products' => $products,
            'cards' => $this->getPromotionCards()
        ]);
    }

    public function getProductCollection($idCollection){
        $products = new Collection();
        $productCollection = ProductCollection::where('idcolecao', $idCollection)->get();

        
        foreach ($productCollection as $product) {
            $products->push(Product::where('id', $product->idproduto)->first());
        }
        
         //Adiciona grade à lista de produtos
         $products = $this->getGrade($products);

               //Busca se existem Preços Promocionais
        $products->map(function ($product) {
            $this->checkPromotion($product);
            //Busca media da avaliação do produto
            $product->media = $this->getRating($this->getReviews($product));
        });

        return $products;
    }


    public function getColors(SupportCollection $products)
    {
        return parent::getColors($products);
    }

    public function getGrade($products)
    {
        return parent::getGrade($products);
    }

    public function getCollectionFiltered()
    {
        $products = new Collection();
        $idcollection = CollectionModel::select('id')
            ->where('descricao', str_replace('-', ' ', request()->input('colecao')))->first();
        //busca produtos da coleção
        $idproducts = ProductCollection::select('idproduto')->where('idcolecao', $idcollection->id)->get();

        foreach ($idproducts as $idproduct) {
            $products->push(Product::where('id', $idproduct->idproduto)->get());
        }


        $collection = CollectionModel::where('descricao', str_slug(request()->input('colecao'),' '))->first();

        //Adiciona grade à lista de produtos
        $products = $this->getGrade($products->first());

        //Caso cor e tamanho sejam enviados
        if (str_slug(request()->input('cor'),' ') != null && request()->input('tamanho') != null) {

            $products = $this->filterByColor($products);
            $products = $this->filterBySize($products);

            $products = PaginateCollectionHelper::paginate($products, 12);

            return view('front.collections.collection', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao', str_replace('-', ' ', request()->input('cor')))->first(),
                'products' => $products,
                'slug' => str_replace('-', ' ', request()->input('colecao')),
                'collection' =>  CollectionModel::where('descricao', str_replace('-', ' ', request()->input('colecao')))->first()
            ]);
        }
        //Caso apenas cor tenha sido enviada
        else if (str_slug(request()->input('cor'),' ') != null && request()->input('tamanho') == null) {

            $products = $this->filterByColor($products);
            $products = $this->getGrade($products);
            $products = PaginateCollectionHelper::paginate($products, 12);

            return view('front.collections.collection', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao', str_replace('-', ' ', request()->input('cor')))->first(),
                'products' => $products,
                'slug' => str_replace('-', ' ', request()->input('colecao')),
                'collection' =>  CollectionModel::where('descricao', str_replace('-', ' ', request()->input('colecao')))->first()
            ]);
        }
        //caso apenas tamanho tenha sido enviado
        else if (str_slug(request()->input('cor'),' ') == null && request()->input('tamanho') != null) {

            $products = $this->filterBySize($products);

            $products = PaginateCollectionHelper::paginate($products, 12);


            return view('front.collections.collection', [
                'colors' => Color::all(),
                'color' => Color::where('descricao', str_replace('-', ' ', request()->input('cor')))->first(),
                'products' => $products,
                'slug' => str_replace('-', ' ', request()->input('colecao')),
                'collection' =>  CollectionModel::where('descricao', str_replace('-', ' ', request()->input('colecao')))->first()
            ]);
        }
    }

    public function getCollectionList()
    {
        $collections = CollectionModel::where('id_empresa', 13)
            ->where('ativa', 1)->get();
        return view('front.collections.collection-list', [
            'collections' => $collections
        ]);
    }
}
