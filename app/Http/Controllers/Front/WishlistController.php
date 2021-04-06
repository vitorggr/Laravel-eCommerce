<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\ProductImages\ProductImage;
use App\Shop\Wishlist\Repositories\Interfaces\WishlistRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    use ProductTransformable;
    /**
     * @var WishlistRepositoryInterface
     */
    private $wishRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    private $cartRepo;

     /**
     * CartController constructor.
     * @param WishlistRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        WishlistRepositoryInterface $wishRepository,
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->wishRepo = $wishRepository;
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
    }
    
    public function store(Request $request)
    {   
        $product = Product::find($request->input('product'));
        $product->name = $product->descricao;
        $product->grade = json_decode($request->input('grade'));
        if(isset($product->grade->valorvendadesconto)):
            $product->price = $product->grade->valorvendadesconto;
        else:
            $product->price = $product->grade->valorvenda;
        endif;
        $options = [];
        $this->wishRepo->addToWishlist($product, $request->input('quantity'), $options);
        $products = $this->wishRepo->getWishlistItemsTransformed();

        return view('front.wishlist.index' ,
         ['products' => $this->getPhotos($products),
        'message' => 'Produto favoritado com sucesso!'
        ]);
    }

    public function destroy($id)
    {
        $this->wishRepo->removeToWishlist($id);
        request()->session()->flash('message', 'Lista atualizada com sucesso!');
        return redirect()->route('favoritos.index');
    }

    public function clear()
    {
        $this->wishRepo->clearWishlist();
        request()->session()->flash('message', 'Lista Resetada Com Sucesso!');
        return redirect()->route('favoritos.index');
    }

    public function index(){
        $products = $this->wishRepo->getWishlistItemsTransformed();
        $products->map(function($product){
            //Busca media da avaliação do produto
            $product->media = $this->getRating($this->getReviews($product));
        });
        return view('front.wishlist.index',
         ['products' => $this->getPhotos($products),
         ]);
    }

    public function add(){
        $items = Session::get('wishlist')['default'];
        foreach ($items as $item) {
            $product = $item->product;
            $qty = $item->qty;
            $product->name = $item->descricao;
            isset($product->grade->valorvenda) ? $product->price = $product->grade->valorvenda : $product->price = 0;
            isset($product->grade->valorvendadesconto) ? $product->price = $product->grade->valorvendadesconto : $product->price = 0;
            $this->cartRepo->addToCart($product, $qty, []);
            $this->destroy($item->rowId);
        }
        request()->session()->flash('message', 'Produtos adicionados à lista com sucesso!');
        return redirect()->route('favoritos.index');
    }

    public function update(Request $request, $id)
    {   
        $this->wishRepo->updateQuantityInWishlist($id, $request->input('quantity'));
        request()->session()->flash('message', 'Lista atualizado com sucesso!');
        return redirect()->route('favoritos.index');
    }

    public function getPhotos($list){
        $list->map(function($product){
            $product->cover = ProductImage::where('idproduto',$product->id)->first()->imgsalvar;
            return $product;
        });
        return $list; 
    }
    
}
