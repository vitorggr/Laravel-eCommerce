<?php

namespace App\Http\Controllers\Front;

use App\Helpers\PaginateCollectionHelper;
use Illuminate\Support\Facades\DB;
use App\Shop\Products\Product;
use App\Shop\Products\ProductReview;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Shop\Categories\Category;
use App\Shop\ProductImages\ProductImage;
use App\Shop\Products\Color;
use App\Shop\Products\ProductColor;
use App\Shop\Products\ProductGrade;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

class ProductController extends Controller
{
    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    /**
     * Busca Lista de Produtos
     * 
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        //Inicialização de variaveis
        $products = new Collection();
        $q = true;
        $baseUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        //Busca por descricao do produto
        $q = request()->input('produto');
        $products->push(Product::where('descricao', 'like', '%' . $q . '%')->get());

        //Busca por descricao da categoria acrescentada pelo método $products->collapse();
        $idcategoria = DB::table('tblcategoria')->select(['id'])->where('descricao', 'like', '%' . $q . '%')->first();

        if (!empty($idcategoria)) {
            $products->push(Product::join('tblprodutocor', 'tblproduto.id', '=', 'tblprodutocor.idproduto')
                ->where('idcategoria', '=', json_decode(json_encode($idcategoria), true))->get());
        }

        // Busca codigo de barra da grade ou codigo ou mensagem na tbl produto caso nao tenham sido encontrados produtos
        if (empty($products)) {
            $products->push(DB::table('tblproduto as t')
                ->join('tblprodutograde as g', 't.id', '=', 'g.idproduto')
                ->join('tblprodutocor as c', 't.id', '=', 'c.idproduto')
                ->where('g.codigobarra', 'like', '%' . $q . '%')
                ->orWhere('t.codigo', '=', $q)
                ->orWhere('t.msg', 'like', '%' . $q . '%')->get());
        }

        //Caso cor e tamanho sejam enviados
        if (str_slug(request()->input('cor'),' ') != null && request()->input('tamanho') != null) {

            $products = $this->filterByColor($products);
            $products = $this->filterBySize($products);
            $products->map(function ($product) {
                $product = $this->checkPromotion($product);
            });
            $products = PaginateCollectionHelper::paginate($products, 12)->setPath($baseUrl);

            return view('front.products.product-search', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao',str_slug(request()->input('cor'),' '))->first(),
                'products' => $products,
                'cards' => $this->getPromotionCards(),
                'q' => $q
            ]);
        }
        //Caso apenas cor tenha sido enviada
        else if (str_slug(request()->input('cor'),' ') != null && request()->input('tamanho') == null) {

            $products = $this->filterByColor($products);
            $products = $this->getGrade($products);
            $products->map(function ($product) {
                $product = $this->checkPromotion($product);
            });
            $products = PaginateCollectionHelper::paginate($products, 12)->setPath($baseUrl);

            return view('front.products.product-search', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao', str_slug(request()->input('cor'),' ') )->first(),
                'products' => $products,
                'cards' => $this->getPromotionCards(),
                'q' => $q
            ]);
        }
        //caso apenas tamanho tenha sido enviado
        else if (str_slug(request()->input('cor'),' ')  == null && request()->input('tamanho') != null) {

            $products = $this->filterBySize($products);
            $products->map(function ($product) {
                $product = $this->checkPromotion($product);
            });
            $products = PaginateCollectionHelper::paginate($products, 12);

            return view('front.products.product-search', [
                //'colors' => array_filter($this->getColors($products)),
                'colors' => Color::all(),
                'color' => Color::where('descricao', str_slug(request()->input('cor'),' '))->first(),
                'products' => $products,
                'cards' => $this->getPromotionCards(),
                'q' => $q
            ]);
        }

        $products = $products->collapse();

        //Adiciona grade à lista de produtos
        $products = $this->getGrade($products);

        //Adiciona Promoções caso hajam
        $products->map(function ($product) {
            $product = $this->checkPromotion($product);
        });

        //Pagina o resultado
        $products = PaginateCollectionHelper::paginate($products, 12)->setPath($baseUrl);


        return view('front.products.product-search', [
            //'colors' => array_filter($this->getColors($products)),
            'colors' => Color::all(),
            'products' => $products,
            'cards' => $this->getPromotionCards(),
            'q' => $q
        ]);
    }
    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {   
        $slug = str_slug($slug,' ');
        $product = Product::where('descricao', 'like', '%' . $slug . '%')->first();
        $product = $this->getGrade($product);
        $category = Category::where('id', $product->idcategoria)->first();
        $reviews = ProductReview::where([['idproduto', $product->id], ['ativo', 1]])->get();
        $setThumbnail = false;

        foreach ($product->images as $image) {
            $tags = DB::table('tblprodutorelacionado')->where('idimagem', $image->id)->get();

            if ($product->idthumbnail && $product->idthumbnail == $image->id) :
                $image->thumbnail = true;
                $setThumbnail = true;
            endif;

            foreach ($tags as $tag) {
                $produtoRelacionado = Product::where('id', $tag->idprodutorelacionado)->first();
                $tag->url = $produtoRelacionado->descricao;
                $tag->posicao = "top:" . $tag->top . "%; left:" . $tag->left . "%;";
            }

            $image->tags = $tags;
        }        

        if (!$setThumbnail) {
            $product->images->first()->thumbnail = true;
        }

        $sizes =  $this->getAvailableSizes($product);
        $this->checkPromotion($product);
        $product->media = $this->getRating($reviews);


        if ($reviews->isEmpty()) {
            $reviews = null;
        };

        if ($tags->isEmpty()) {
            $tags = null;
        };

        if (isset($product->grade->descricao)) {
            $selectedSize = $product->grade->descricao;
        } else $selectedSize = null;

        return view('front.products.product', compact(
            'selectedSize',
            'product',
            'category',
            'reviews',
            'sizes'
        ));
    }

    public function getProductGrade()
    {
        $slug = request()->input('produto');
        $product = Product::where('descricao', 'like', '%' . $slug . '%')->first();
        $product = $this->getGrade($product);
        $selectedSize = request()->input('tamanho');
        $product->grade = ProductGrade::where([
            ['descricao', $selectedSize],
            ['idproduto', $product->id]
        ])->first();
        $category = Category::where('id', $product->idcategoria)->first();
        $reviews = ProductReview::where([['idproduto', $product->id], ['ativo', 1]])->get();
        $sizes =  $this->getAvailableSizes($product);
        //$this->checkPromotion($product);
        //$product->valorvenda = (isset($produto->valorvenda)) ? $produto->valorvenda : 0;
        if (count($reviews) != 0) {
            $avg = $this->getRating($reviews) / count($reviews);
            $product->media = $avg;
        }
        if ($reviews->isEmpty()) {
            $reviews = null;
        };

        $this->checkPromotion($product);
        return view('front.products.product', compact(
            'selectedSize',
            'product',
            'category',
            'reviews',
            'sizes'
        ));
    }


    public function getAvailableSizes($product)
    {
        $grades = new Collection();
        $grade = ProductGrade::select('descricao', 'valorvenda')->where('idproduto', $product->id)->get();

        $produto_promo = $this->checkPromotion($product);

        for ($i = 0; $i < sizeof($produto_promo->grades); $i++) {

            for ($e = 0; $e < sizeof($grade); $e++) {

                if ($produto_promo->grades[$i]->descricao == $grade[$e]->descricao) {
                    $grades->push([
                        'name' => $grade[$e]->descricao,
                        'price' => $grade[$e]->valorvenda,
                        'price_promo' => $produto_promo->grades[$i]->valorvendadesconto
                    ]);
                }
            }
        }

        $grades->toBase()->merge($grade);
        $grades = $grades->keyBy('name');

        return $grades;
    }

    public function createReview(Request $request)
    {
        $product = Product::where('id', $request->idproduto)->first();

        $review = new ProductReview();
        $review->idproduto = $request->idproduto;
        $review->idcliente = auth()->user()->Id;
        $review->assunto = $request->subject;
        $review->mensagem = $request->msg;
        $review->avaliacao = $request->star;
        $review->ativo = true;
        $review->data = $request->created_at;
        $review->save();
        return $this->show($product->descricao);
    }

    public function filterBySize($products)
    {

        if ($products->first() instanceof Collection) {
            $products = $products->first();
        }

        //Seta as grades presentes em cada produto=
        $products->map(function ($product) {
            $grades = new Collection();
            $grade = ProductGrade::where('idproduto', $product->id)->get();
            if ($grade->isNotEmpty()) {
                foreach ($grade as $item) {
                    $grades->push($item);
                }
            }
            $product->grades = $grades;
            return $product;
        });
        //Elimina da lista os produtos que não contem o tamanho escolhido
        $products = $products->reject(function ($product) {
            foreach ($product->grades as $grade) {
                if ($grade->descricao == str_replace(request()->input('tamanho'), '-', ' ')) {
                    $product->grade = $grade;
                    return false;
                }
            }
            return true;
        });
        return $products;
    }

    public function filterByColor($products)
    {
        //Seta os ID's das cores presentes em cada produto
        $products = $products->first();
        $products->map(function ($product) {
            $colors = new Collection();
            $color = ProductColor::select('idcor')->where('idproduto', $product->id)->get();
            if ($color->count() != 0) {
                foreach ($color as $item) {
                    $colors->push($item->idcor);
                }
            }
            $product->colors = $colors;

            //Seta as grades dos produtos presentes na lista
            /*$grades = new Collection();
            $grade = ProductGrade::where('idproduto', $product->id)->get();
            if ($grade->isNotEmpty()) {
                foreach ($grade as $item) {
                    $grades->push($item);
                }
            }
            $product->grades = $grades->first();
            */
            if (!isset($product->grade)) {
                $product->grade = ProductGrade::where('idproduto', $product->id)->get();
            }

            return $product;
        });



        //Elimina da lista os produtos que não contem a cor escolhida 
        $products = $products->reject(function ($product) {
            $idcolor = Color::select('id')->where('descricao', 'like', '%' . str_replace(request()->input('cor'), '-', ' ') . '%')->first();
            foreach ($product->colors as $color) {
                if ($color == $idcolor->id) {
                    return false;
                }
            }
            return true;
        });

        return $products;
    }

    public function getColors(SupportCollection $products)
    {
        return parent::getColors($products);
    }
    
}
