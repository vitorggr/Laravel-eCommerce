<?php

namespace App\Http\Controllers\Front;

// use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;

use App\Helpers\CreatePreListaPostagemHelper;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Providers\BoletoServiceProvider;
use App\Providers\PixServiceProvider;
use App\Shop\Addresses\Address;
use App\Providers\SigepServiceProvider;
use App\Shop\Orders\Order;
use App\Shop\Orders\OrderCharge;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\PaymentMethods\Boleto\Boleto;
use Gerencianet\Request;
use Illuminate\Support\Facades\DB;
use PhpSigep\Services\SoapClient\Real;

class AccountsController extends Controller
{
    use OrderTransformable;


    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;
    protected $clienteId;
    protected $clientSecret;


    /**
     * AccountsController constructor.
     *
     * @param CourierRepositoryInterface $courierRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepo = $customerRepository;
    }

    public function index()
    {
        // $service = new PixServiceProvider();
        // dd($service->createDevolution('3fb0970f80d84d2fbcd5708563738fd0'));
        $service = new BoletoServiceProvider();
        $customer = $this->customerRepo->findCustomerById(auth()->user()->Id);
        $customerRepo = new CustomerRepository($customer);
        $orders = Order::where('idcliente', auth()->user()->Id)->get();
        $addresses = Address::where('cliente_id', auth()->user()->Id)->get();

        $orders->map(function ($order) {
            $order['valortotal'] = str_replace(',', '.', $order['valortotal']);
        });



        if (isset($orders->first()->id)) :
            foreach ($orders as $order) {
                //BOLETO
                if ($order->idforma == 1) {
                    $order->formaPagamento = DB::table('tblforma_padrao')->where('id', $order->idforma)->first();
                    $order->orderItem = DB::table('tblpedidoitemproduto')->where('idpedido', $order->id)->get();
                    $order->boleto = OrderCharge::where('idpedido', $order->id)->first();
                    $order->status = $service->getBoletoStatus($order->boleto->codigo);
                    $history = $order->status['data']['history'];
                    $order->history = end($history)['message'];
                    // $service = new BoletoServiceProvider();
                    // dd($service->getBoletoStatus($order->boleto->codigo));
                    
                //PIX
                } else if ($order->idforma == 6) {
                    $order->formaPagamento = DB::table('tblforma_padrao')->where('id', $order->idforma)->first();
                    $order->orderItem = DB::table('tblpedidoitemproduto')->where('idpedido', $order->id)->get();
                    $order->pix = OrderCharge::where('idpedido', $order->id)->first();
                    $history = json_decode($this->curlGetContent('https://'.$order->pix->payload));
                    $order->history = $history->mensagem;
                    // $service = new PixServiceProvider();
                    // dd($service->getCharge($order->pix->codigo));
                }
            }
        endif;



        return view('front.accounts', [
            'customer' => $customer,
            'orders' => $orders,
            'addresses' => $addresses
        ]);
    }

    public function update()
    {

        if (auth()->user()->Id == request()->input('id')) :
            $customer = $this->customerRepo->findCustomerById(request()->input('id'));

            if (request()->input('Documento') && request()->input('Documento') != null) {
                request()->merge(array('Documento' => preg_replace('/[^0-9]/', '', request()->input('Documento'))));
            }

            $customer->update(array_filter(request()->except('_token', 'id')));

            return redirect()->route('conta', ['tab' => 'perfil'])
                ->with('message', 'Conta atualizado com sucesso!');
        endif;

        return redirect()->route('conta', ['tab' => 'perfil'])
            ->with('error', 'Conta não pode ser atualizada!');
    }

    public function curlGetContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $html = curl_exec($ch);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function rastreaObjeto($etiquetaSolicitada)
    {

        $etiquetasFromQueryRaw = trim((isset($_GET['etiquetas']) ? $_GET['etiquetas'] : ''));
        // $etiquetas = $etiquetaSolicitada->getResult();
        $etiquetas = array();
        if ($etiquetasFromQueryRaw) {
            $etiquetasFromQuery = explode(',', $etiquetasFromQueryRaw);
            foreach ($etiquetasFromQuery as $etiquetaFromQuery) {
                $etiqueta = new \PhpSigep\Model\Etiqueta();
                $etiqueta->setEtiquetaComDv(trim($etiquetaFromQuery));
                $etiquetas[] = $etiqueta;
            }
        }

        if (count($etiquetas)) {
            $sigepServiceProvider = new SigepServiceProvider();
            $accessDataDeHomologacao = $sigepServiceProvider->getAccess();
            $accessDataDeHomologacao->setUsuario('ECT'); // Usuário e senha para teste passado no manual
            $accessDataDeHomologacao->setSenha('SRO');

            $params = new \PhpSigep\Model\RastrearObjeto();
            $params->setAccessData($accessDataDeHomologacao);
            $params->setEtiquetas($etiquetas);

            $phpSigep = new Real();
            return $phpSigep->rastrearObjeto($params);
        }
    }

    public function getEtiqueta()
    {
        $sigepServiceProvider = new SigepServiceProvider();
        $accessDataDeHomologacao = $sigepServiceProvider->getAccess();
        $usuario = trim((isset($_GET['usuario']) ? $_GET['usuario'] : $accessDataDeHomologacao->getUsuario()));
        $senha = trim((isset($_GET['senha']) ? $_GET['senha'] : $accessDataDeHomologacao->getSenha()));
        $cnpjEmpresa = $accessDataDeHomologacao->getCnpjEmpresa();

        $accessData = new \PhpSigep\Model\AccessData();
        $accessData->setUsuario($usuario);
        $accessData->setSenha($senha);
        $accessData->setCnpjEmpresa($cnpjEmpresa);

        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas(1);
        $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_04510);
        $params->setAccessData($accessData);

        $phpSigep = new Real();

        return $phpSigep->solicitaEtiquetas($params);
    }

    public function getEtiquetaComDV($etiquetasSemDv)
    {

        $phpSigep = new Real();
        $sigepServiceProvider = new SigepServiceProvider;
        $params = new \PhpSigep\Model\GeraDigitoVerificadorEtiquetas();
        $params->setAccessData($sigepServiceProvider->getAccess());
        $params->setEtiquetas($etiquetasSemDv);
        $result = $phpSigep->geraDigitoVerificadorEtiquetas($params)->getResult();
        foreach ($result as $item) {
            $item = $item->getEtiquetaComDV($item);
        }
        /** @var $etiquetasComDv \PhpSigep\Model\Etiqueta[] */
        return $result;
    }

    public function getBoletoStatus(Request $request)
    {
        $service = new BoletoServiceProvider();
        return $service->getBoletoStatus($request->chargeId);
    }
}
