<?php

namespace App\Http\Controllers\Front;

use App\Helpers\PaginateCollectionHelper;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Categories\Category;
use App\Shop\Products\Color;
use App\Shop\Products\Product;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection;

use function GuzzleHttp\json_decode;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * Find the category via the slug
     *
     * @param string $slug
     * @return \App\Shop\Categories\Category
     */
    public function getCategory(string $slug)
    {
        $products = new Collection();
        $idcategoria = Category::select('id')->where('descricao',str_replace('-',' ',$slug))->first();
        //busca produtos da coleção

        $products->push(Product::where([
            ['idcategoria', $idcategoria->id],
            ['loja', 1],
            ['status', 1]
        ])->get());

        $category = Category::where('descricao',str_replace('-',' ',$slug))->first();

        //Adiciona grade à lista de produtos
        $products = $this->getGrade($products->first());
        
        $products->map(function($product){
            //Busca se existem Preços Promocionais
            $this->checkPromotion($product);
            //Busca media da avaliação do produto
            $product->media = $this->getRating($this->getReviews($product));
        });
        
        

        //Pagina o resultado
        $products = PaginateCollectionHelper::paginate($products, 12);

        return view('front.categories.category', [
            'colors' => Color::all(),
            'slug' => str_slug($slug,'-'),
            'category' => $category,
            'products' => $products,
            'cards' => $this->getPromotionCards()
        ]);
    }

    public function getCategoryList()
    {
        $categories = Category::where('idempresa',13)->get();
        return view('front.categories.category-list',[
            'categories' =>$categories
            ]);
    }

    public function getCategoryFiltered()
    {   
        $slugCategory = str_slug(request()->input('categoria'),' ');
        $products = new Collection();
        $idcategoria = Category::select('id')->where('descricao',$slugCategory)->first();

        //busca produtos da coleção
        $products->push(Product::where([
            ['idcategoria', $idcategoria->id],
            ['loja', 1],
            ['status', 1]
        ])->get());

        $category = Category::where('descricao',$slugCategory)->first();

        //Adiciona grade à lista de produtos
        $products = $this->getGrade($products->first());

        //Caso cor e tamanho sejam enviados
        $slugColor=str_slug(request()->input('cor'),' ');
        if ($slugColor != null && request()->input('tamanho') != null) {

            $products = $this->filterByColor($products);
            $products = $this->filterBySize($products);

            $products = PaginateCollectionHelper::paginate($products, 12);

            return view('front.categories.category', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao', request()->input('cor'))->first(),
                'products' => $products,
                'slug' => request()->input('categoria'),
                'category' => $category
            ]);
        }
        //Caso apenas cor tenha sido enviada
        else if ($slugColor != null && request()->input('tamanho') == null) {

            $products = $this->filterByColor($products);
            $products = $this->getGrade($products);
            $products = PaginateCollectionHelper::paginate($products, 12);

            return view('front.categories.category', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao', request()->input('cor'))->first(),
                'products' => $products,
                'slug' => request()->input('categoria'),
                'category' => $category
            ]);
        }
        //caso apenas tamanho tenha sido enviado
        else if (request()->input('cor') == null && request()->input('tamanho') != null) {

            $products = $this->filterBySize($products);

            $products = PaginateCollectionHelper::paginate($products, 12);

            return view('front.categories.category', [
                'colors' => Color::all(),
                'color' => Color::where('descricao', request()->input('cor'))->first(),
                'products' => $products,
                'slug' => request()->input('categoria'),
                'category' => $category
            ]);
        }
    }


    public function getColors(SupportCollection $products)
    {
        return parent::getColors($products);
    }

    public function getGrade($products)
    {
        return parent::getGrade($products);
    }
}
