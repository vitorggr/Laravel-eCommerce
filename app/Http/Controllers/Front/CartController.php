<?php

namespace App\Http\Controllers\Front;

use App\Shop\Carts\Requests\AddToCartRequest;
use App\Shop\Carts\Requests\UpdateCartRequest;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\ProductAttributes\Repositories\ProductAttributeRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Http\Controllers\Controller;
use App\Shop\Coupon\Coupon;
use App\Shop\Orders\Order;
use App\Shop\Wishlist\Repositories\Interfaces\WishlistRepositoryInterface;
use DateTime;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Break_;

class CartController extends Controller
{
    use ProductTransformable;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $productAttributeRepo;



    private $desconto;
    /**
     * CartController constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     * @param WishlistRepositoryInterface $wishRepository
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository,
        WishlistRepositoryInterface $wishRepository,
        ProductAttributeRepositoryInterface $productAttributeRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
        $this->wishRepo = $wishRepository;
        $this->productAttributeRepo = $productAttributeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $descontoAtivo = false;
        $total = $this->cartRepo->getTotal(2, null, $this->desconto);
        /* lógica do cupom */
        if (request()->input('ativo') == 1) {
            $cupom = Coupon::where([
                ['idempresa', 13],
                ['codigo', request()->input('codigo')]
            ])->first();


            if (isset($cupom)) {
                $limite = Order::select()->where('idcupom', $cupom->id)->count();
                switch ($cupom) {
                    case $cupom->ativo == 0:
                        session()->flash('error', 'Cupom Inativo!');
                        break;
                    case date("Y-m-d") > $cupom->validade:
                        session()->flash('error', 'Cupom Vencido!');
                        break;
                    case $limite >= $cupom->limite:
                        session()->flash('error', 'Limite de usos do cupom atingido');
                        break;
                    default:
                        if (isset($cupom->desconto)) {
                            $this->desconto = $cupom->desconto;
                            $descontoAtivo = true;
                            $total = $total - $cupom->desconto;
                        }elseif(isset($cupom->descontopercentual)){
                            $this->desconto = $cupom->descontopercentual;
                            $descontoAtivo = true;
                            $total = $total - ($total * $cupom->descontopercentual/100);
                        }
                        session()->flash('message', 'Cupom ' . $cupom->codigo . ' adicionado com sucesso!');
                        break;
                }
            } else session()->flash('error', 'Código de Cupom Inválido');
        } else {
            $cupom = null;
            $this->clearFlash();
        };

        if ($total < 0) {
            $total = 0;
        }

        return view('front.carts.cart', [
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'subtotal' => $this->cartRepo->getSubTotal(),
            'tax' => $this->cartRepo->getTax(),
            'total' => $total,
            'cupom' => $cupom,
            'descontoAtivo' => $descontoAtivo
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    {
        $product = Product::find($request->input('product'));
        $product = $this->getGrade($product);
        $this->checkPromotion($product);
        if (isset($product->grade->valorvendadesconto)) {
            $product->price = $product->grade->valorvendadesconto;
        } else {
            $product->price = $product->grade->valorvenda;
        }
        if (isset($product->grade->peso)) {
            $product->weight = $product->grade->peso;
        } else {
            $product->weight = 0.1;
        }
        $options = [$product->grade->descricao];
        $product->name = $product->descricao;
        $product->quantidade = $request->input('quantity');

        if (isset(session()->get('cart')['default'])) {
            $session = session()->get('cart')['default'];
            if ($session != null && !$session->contains(json_decode($request->input('grade')))) {
                $rowId = $this->generateRowId($product->id, $options);
                $cartItem = new CartItem(
                    $product->id,
                    $product->name,
                    $product->price,
                    $product->weight,
                    $options
                );
                $cartItem->tamanho = $product->grade->descricao;
                $cartItem->qty = intval(request()->input('quantity'));
                $cartItem->product = $product;
                $session[$rowId] = $cartItem;
                return redirect()->route('carrinho.index')->with('message', 'Produto adicionado com sucesso!');
            }
        }

        $this->cartRepo->addToCart($product, $request->input('quantity'), $options);
        return redirect()->route('carrinho.index')->with('message', 'Produto adicionado com sucesso!');
    }

    public function applyCoupon()
    {
        $desconto = Coupon::where([
            ['idempresa', 13],
            ['codigo', request()->input('codigo')]
        ])->first();

        if (isset($desconto)) {
            switch ($desconto) {
                case $desconto->ativo == 0:
                    return redirect()->route('carrinho.index')->with('error', 'Cupom Inativo');
                    break;
                case date("Y-m-d") > $desconto->validade:
                    return redirect()->route('carrinho.index')->with('error', 'Cupom Vencido');
                    break;
                default:
                    return redirect()->route('carrinho.index')->with('message', 'Cupom adicionado com sucesso!');
                    break;
            }
        } else
            return redirect()->route('carrinho.index')->with('error', 'Cupom não encontrado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, $qty)
    {
        $this->cartRepo->updateQuantityInCart($id, $qty);
        request()->session()->flash('message', 'Carrinho de compras atualizado com sucesso!');
        return redirect()->route('carrinho.index');
    }

    public function upgrade($id, $qty)
    {
        $this->cartRepo->updateQuantityInCart($id, $qty + 1);
        request()->session()->flash('message', 'Carrinho de compras atualizado com sucesso!');
        return redirect()->route('carrinho.index');
    }

    public function downgrade($id, $qty)
    {
        $this->cartRepo->updateQuantityInCart($id, $qty - 1);
        request()->session()->flash('message', 'Carrinho de compras atualizado com sucesso!');
        return redirect()->route('carrinho.index');
    }

    public function clear()
    {
        $this->cartRepo->clearCart();
        request()->session()->flash('message', 'Carrinho Limpo Com Sucesso!');
        return redirect()->route('carrinho.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartRepo->removeToCart($id);
        request()->session()->flash('message', 'Carrinho de compras atualizado com sucesso!');
        return redirect()->route('carrinho.index');
    }
}
