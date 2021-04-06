<?php

namespace App\Http\Controllers\Front;

use App\Helpers\SigepHelper;
use Illuminate\Support\Facades\DB;
use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Cart\Requests\CartCheckoutRequest;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Carts\Requests\PayPalCheckoutExecutionRequest;
use App\Shop\Carts\Requests\StripeExecutionRequest;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\PaymentMethods\Paypal\Exceptions\PaypalRequestError;
use App\Shop\PaymentMethods\Paypal\Repositories\PayPalExpressCheckoutRepository;
use App\Shop\PaymentMethods\Stripe\Exceptions\StripeChargingErrorException;
use App\Shop\PaymentMethods\Stripe\StripeRepository;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Shipping\ShippingInterface;
use Exception;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Providers\BoletoServiceProvider;
use App\Providers\GerenciaNetServiceProvider;
use App\Providers\PixServiceProvider;
use App\Providers\SigepServiceProvider;
use App\Shop\Addresses\Address;
use App\Shop\Coupon\Coupon;
use App\Shop\Orders\Order;
use App\Shop\OrdersItems\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PayPal\Exception\PayPalConnectionException;
use App\Shop\Carts\Requests\CieloCheckoutExecutionRequest;
use App\Shop\Orders\OrderCharge;
use App\Shop\PaymentMethods\Cielo\CieloRepository;
use App\Shop\Products\Product;
use App\Shop\Products\ProductGrade;
use Illuminate\Support\Facades\Redirect;
use PhpSigep\Model\CalcPrecoPrazo;
use PhpSigep\Model\Dimensao;
use PhpSigep\Services\SoapClient\Real;

class CheckoutController extends Controller
{
    use ProductTransformable;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * @var PayPalExpressCheckoutRepository
     */
    private $payPal;

    /**
     * @var ShippingInterface
     */
    private $shippingRepo;

    protected $cepOrigem = "30411-127";

    protected $shipping, $cart, $descontoGeral, $descontoCondicional, $altura, $largura, $comprimento, $peso, $products;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        AddressRepositoryInterface $addressRepository,
        CustomerRepositoryInterface $customerRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository,
        ShippingInterface $shipping
    ) {
        $this->cartRepo = $cartRepository;
        $this->addressRepo = $addressRepository;
        $this->customerRepo = $customerRepository;
        $this->productRepo = $productRepository;
        $this->orderRepo = $orderRepository;
        $this->shippingRepo = $shipping;
    }



    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $this->clearFlash();
        $this->products = $this->cartRepo->getCartItems();
        $customer = auth()->user();
        $newAddress = null;
        $shipping = null;
        $endereco = Address::where('cliente_id', auth()->user()->Id)->first();

        if (session()->get('cart')['default']) {
            $this->cart = session()->get('cart')['default']->first();
        }

        if (request()->input('cupom') != null) {
            $cupom = Coupon::where('codigo', request()->input('cupom'))->first();
            if (isset($cupom)) {
                $this->checkCoupon($cupom);
            }
        } else {
            $cupom = null;
        };

        if (request()->input('frete') == true) {
            $shipping = $this->getFrete();
        }

        if (request()->input('cep') != null) {
            $newAddress = $this->getCep();
        }

        return view('front.checkout', [
            'endereco' => $endereco,
            'cupom' => $cupom,
            'descontoGeral' => $this->descontoGeral,
            'descontoCondicional' => $this->descontoCondicional,
            'customer' => $customer,
            'addresses' => Address::where('cliente_id', auth()->user()->Id)->get(),
            'products' => $this->products,
            'subtotal' => $this->cartRepo->getSubTotal(2),
            'tax' => $this->cartRepo->getTax(),
            'total' => $this->cartRepo->getTotal(2),
            'totalDesconto' => $this->getDiscount($this->products),
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'newAddress' => $newAddress,
            'shipping' => $shipping
        ]);
    }

    public function storeAddress(Request $request)
    {
        $request['cliente_id'] = auth()->user()->Id;
        Address::create($request->all());
        return redirect('checkout')->with('message', 'Endereço adicionado com sucesso!');
    }


    public function getCep()
    {
        new SigepServiceProvider();
        $phpSigep = new Real();
        $result = $phpSigep->consultaCep(request()->input('cep'));
        if ($result->getResult() == null && request()->input('cep') != null) {
            session()->flash('error', Str::title($result->getErrorMsg()));
        }
        return $result->getResult();
    }

    public function getFrete()
    {
        $sigepService = new SigepServiceProvider();
        $sigep = new SigepHelper();
        if ($sigep->getDisponibilidadeServico($this->cepOrigem) == true) {
            $serviceSigep = new Real();

            foreach ($this->cartRepo->getCartItems() as $item) {
                $grade = $item->product->grade->first();
                $this->altura += ($grade['altura'] * $item->qty);
                $this->comprimento += ($grade['comprimento'] * $item->qty);
                $this->largura += ($grade['largura'] * $item->qty);
                $this->peso += ($grade['peso'] * $item->qty);
            }

            $dimensao = new Dimensao();
            $dimensao->setTipo(Dimensao::TIPO_PACOTE_CAIXA);
            $dimensao->setAltura($this->altura); // em centímetros
            $dimensao->setComprimento($this->comprimento); // em centímetros
            $dimensao->setLargura($this->largura); // em centímetros

            $parametros = new CalcPrecoPrazo();
            $parametros->setAccessData($sigepService->getAccess());
            $parametros->setCepOrigem($this->cepOrigem);
            $parametros->setCepDestino(Address::select('cep')->where('id', request()->billing_address)->get()->first()->cep);
            $parametros->setServicosPostagem(\PhpSigep\Model\ServicoDePostagem::getAll());
            $parametros->setAjustarDimensaoMinima(true);
            $parametros->setDimensao($dimensao);
            $parametros->setPeso($this->peso); // em gramas
            $serviceSigep = new Real();

            return $serviceSigep->calcPrecoPrazo($parametros)->getResult();
        } else {
            return session()->flash('error', 'Serviço Não Disponivel No CEP de destino adicionado');
        }
    }

    public function getPlpParameters()
    {
        foreach ($this->cartRepo->getCartItems() as $item) {
            $grade = $item->product->grade->first();
            $this->altura += ($grade['altura'] * $item->qty);
            $this->comprimento += ($grade['comprimento'] * $item->qty);
            $this->largura += ($grade['largura'] * $item->qty);
            $this->peso += ($grade['peso'] * $item->qty);
        }

        $dimensao = new Dimensao();
        $dimensao->setTipo(Dimensao::TIPO_PACOTE_CAIXA);
        $dimensao->setAltura($this->altura); // em centímetros
        $dimensao->setComprimento($this->comprimento); // em centímetros
        $dimensao->setLargura($this->largura); // em centímetros
        $dimensao->peso = $this->peso;

        return $dimensao;
    }

    /**
     * Checkout the items
     *
     * @param CartCheckoutRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Addresses\Exceptions\AddressNotFoundException
     * @throws \App\Shop\Customers\Exceptions\CustomerPaymentChargingErrorException
     * @codeCoverageIgnore
     */

    public function store(CartCheckoutRequest $request)
    {
        $shippingFee = 0;

        switch ($request->input('payment')) {
            case 'paypal':
                return $this->payPal->process($shippingFee, $request);
                break;
            case 'stripe':

                $details = [
                    'description' => 'Stripe payment',
                    'metadata' => $this->cartRepo->getCartItems()->all()
                ];

                $customer = $this->customerRepo->findCustomerById(auth()->id());
                $customerRepo = new CustomerRepository($customer);
                $customerRepo->charge($this->cartRepo->getTotal(2, $shippingFee), $details);
                break;
            default:
        }
    }
    /**
     * Execute the PayPal payment
     *
     * @param PayPalCheckoutExecutionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Execute the Cielo payment
     *
     * @param CieloCheckoutExecutionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function executeCieloPayment(Request $request)
    {
        $cieloRepo = new CieloRepository();
        $cieloRepo->execute($request);

        $this->cartRepo->clearCart();

        return $this->success();
    }

    public function executeBoletoPayment(Request $request)
    {
        $service = new BoletoServiceProvider();
        $response = $service->makeBoleto($this->cartRepo->getCartItems(), $request);
        return $this->payment($response['data']);
    }

    public function executePixPayment(Request $request){
        $service = new PixServiceProvider();
        $response = $service->createCharge($this->cartRepo->getCartItems(),$request->shipping);
        return $this->payment($response);
    }

    public function payment($response)
    {
        if ($response['payment'] == 'banking_billet') {
            try {
                Order::insert([
                    "data" => date('Y-m-d'),
                    "idcliente" => auth()->user()->Id,
                    "valortotal" => $response['total'] / 100,
                    "idforma" => 1
                ]);
                OrderCharge::insert([
                    "idpedido" => Order::max('id'),
                    "expira" => $response['expire_at'],
                    "codigo" => strval($response['charge_id']),
                    "codigodebarras" => $response['barcode'],
                    "urlboleto" => $response['pdf']['charge']
                ]);
                foreach ($this->cartRepo->getCartItems() as $item) {
                    OrderItem::insert([
                        "qtd" => $item->qty,
                        "valor" => $item->price,
                        "idgrade" => ProductGrade::where([
                            ["idproduto", $item->id],
                            ["descricao", $item->options[0]]
                        ])->first()->id,
                        "idproduto" => $item->id,
                        "idpedido" => Order::max('id')
                    ]);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }else if($response['payment'] == 'pix'){
            try {
                $date = explode("U",date($response['payload']['calendario']['criacao']))[0];
                Order::insert([
                    "data" => $date,
                    "idcliente" => auth()->user()->Id,
                    "valortotal" => doubleval($response['payload']['valor']['original']),
                    "idforma" => 6
                ]);
                OrderCharge::insert([
                    "idpedido" => Order::max('id'),
                    "expira" => date("Y-m-d", strtotime('+1 hour', strtotime($date))),
                    "codigo" => $response['payload']['txid'],
                    "payload" => $response['payload']['location'],
                    "qrcode" => $response['qrcode']['imagemQrcode']
                ]);
                foreach ($this->cartRepo->getCartItems() as $item) {
                    OrderItem::insert([
                        "qtd" => $item->qty,
                        "valor" => $item->price,
                        "idgrade" => ProductGrade::where([
                            ["idproduto", $item->id],
                            ["descricao", $item->options[0]]
                        ])->first()->id,
                        "idproduto" => $item->id,
                        "idpedido" => Order::max('id')
                    ]);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return redirect()->route("conta", ['tab' => 'pedidos']);
    }

    /**
     * @param StripeExecutionRequest $request
     * @return \Stripe\Charge
     */
    public function charge(StripeExecutionRequest $request)
    {
        try {
            $customer = $this->customerRepo->findCustomerById(auth()->id());
            $stripeRepo = new StripeRepository($customer);
            $stripeRepo->execute(
                $request->all(),
                Cart::total(),
                Cart::tax()
            );
            return redirect()->route('checkout.success')->with('message', 'Stripe payment successful!');
        } catch (StripeChargingErrorException $e) {
            Log::info($e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'There is a problem processing your request.');
        }
    }
    /**
     * Cancel page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancel(Request $request)
    {
        return view('front.checkout-cancel', ['data' => $request->all()]);
    }

    /**
     * Success page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success()
    {
        return view('front.checkout-success');
    }

    /**
     * @param Customer $customer
     * @param Collection $products
     *
     * @return mixed
     */
    private function createShippingProcess(Customer $customer, Collection $products)
    {
        $customerRepo = new CustomerRepository($customer);

        if ($customerRepo->findAddresses()->count() > 0 && $products->count() > 0) {

            $this->shippingRepo->setPickupAddress();
            $deliveryAddress = $customerRepo->findAddresses()->first();
            $this->shippingRepo->setDeliveryAddress($deliveryAddress);
            $this->shippingRepo->readyParcel($this->cartRepo->getCartItems());

            return $this->shippingRepo->readyShipment();
        }
    }

    public function getProductsCouponPrice($cartItems, $cupom)
    {
        //Checa se o cupom está relacionado a categoria
        if (isset($cupom->idcategoria)) {
            //itera entre items  do carrinho
            foreach ($cartItems as $item) {
                //reseta valor de desconto
                $item->couponPrice = null;
                //Pega id categoria do produto do carrinho
                $produtoCategoria = DB::table('tblproduto')->where('id', $item->id)->select('idcategoria')->first();
                //compara se a categoria do produto é o mesmo do cupom
                if ($produtoCategoria->idcategoria == $cupom->idcategoria) {
                    //checa se o cupom é de desconto unitario
                    if ($cupom->desconto > 0) {
                        //seta novo valor ao produto
                        $item->couponPrice = $item->price - $cupom->desconto;
                        //checa se o cupom é de desconto percentual
                    } else if ($cupom->descontopercentual > 0) {
                        //seta novo valor ao produto
                        $item->couponPrice = $item->price - ($item->price * intval($cupom->descontopercentual) / 100);
                    }
                }
            };

            //Checa se o cupom está relacionado a uma coleção
        } elseif (isset($cupom->idcolecao)) {
            //itera entre items  do carrinho
            foreach ($cartItems as $item) {
                //reseta valor de desconto
                $item->couponPrice = null;
                //pega id coleção do produto no carrinho
                $produtocolecao = DB::table('tblprodutocolecao')->where('idproduto', $item->id)->first();
                //compara se a colecao do produto é o mesmo do cupom
                if (isset($produtocolecao) && $produtocolecao->idcolecao  == $cupom->idcolecao) {
                    //checa se o cupom é de desconto unitario
                    if ($cupom->desconto > 0) {
                        //seta novo valor ao produto
                        $item->couponPrice = $item->price - $cupom->desconto;
                        //checa se o cupom é de desconto percentual
                    } else if ($cupom->descontopercentual > 0) {
                        //seta novo valor ao produto
                        $item->couponPrice = $item->price - (($item->price * $cupom->descontoPercentual) / 100);
                    }
                }
            };
        }

        return $cartItems;
    }

    public function checkCoupon($cupom)
    {
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
                if ($cupom->condicional != 1) {
                    $this->cart->cupom = $cupom;
                    session()->flash('message', 'Cupom ' . $cupom->codigo . ' adicionado com sucesso!');
                    $this->descontoGeral = true;
                    break;
                } elseif ($cupom->condicional == 1) {
                    $this->products = $this->getProductsCouponPrice($this->products, $cupom);
                    session()->flash('message', 'Cupom ' . $cupom->codigo . ' adicionado com sucesso!');
                    $this->descontoCondicional = true;
                    break;
                }
        }
    }

    public function getDiscount($cartItems)
    {
        $totalDesconto = 0;
        foreach ($cartItems as $item) {
            isset($item->couponPrice) ? $totalDesconto += $item->couponPrice : $totalDesconto += $item->price;
        }
        return $this->cartRepo->getTotal() - $totalDesconto;
    }
}
