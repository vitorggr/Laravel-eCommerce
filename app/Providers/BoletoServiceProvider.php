<?php

namespace App\Providers;

use App\Shop\Boleto\GerenciaNetConfig;
use App\Shop\Coupon\Coupon;
use Illuminate\Support\ServiceProvider;
use Exception;
use Gerencianet\Gerencianet;
use Gerencianet\Exception\AuthorizationException;
use Gerencianet\Exception\GerencianetException;
use Illuminate\Database\Eloquent\Collection;

class BoletoServiceProvider extends ServiceProvider
{
    private $clienteIdDev = 'Client_Id_47ddba3b9b90c12e704d2684e6510af66fda2167';
    private $clientSecretDev = 'Client_Secret_10da097f668dac7bc3e6b13fbbf79f8d6d04f25a';
    private $notificationUrl = 'https://www.easyshop.com.br/loja/';

    public function __construct()
    {
    }

    public function changeNotificationUrl($charge_id,$request)
    {

        $options = [
            'client_id' => $this->clienteIdDev,
            'client_secret' => $this->clientSecretDev,
            'sandbox' => true // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];

        // $charge_id refere-se ao ID da transação ("charge_id")
        $params = [
            'id' => $charge_id
        ];

        $body = [
            'custom_id' => $request->idpedido, // associar transação Gerencianet com seu identificador próprio
            'notification_url' => $request->url // url de notificação
        ];

        try {
            $api = new \Gerencianet\Gerencianet($options);
            $api->updateChargeMetadata($params, $body);
        } catch (GerencianetException $e) {
            return $e;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function makeBoleto($cartItems, $request)
    {
        $customer = auth()->user();
        $options = [
            'client_id' => $this->clienteIdDev,
            'client_secret' => $this->clientSecretDev,
            'sandbox' => true // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];

        $items = new Collection();

        $shippings = new Collection();
        foreach ($cartItems as $cartItem) {
            if (isset($cartItem->couponPrice)) {
                $item = [
                    'name' => $cartItem->name,
                    'amount' => $cartItem->qty,
                    'value' => $cartItem->couponPrice * 100
                ];
            } else {
                $item = [
                    'name' => $cartItem->name,
                    'amount' => $cartItem->qty,
                    'value' => $cartItem->price * 100
                ];
            }
            $items->push($item);
        }
        $shipping = [
            'name' => 'Frete',
            'value' => intval($request->shipping * 100)
        ];

        $shippings->push($shipping);

        $metadata = array('notification_url' => $this->notificationUrl); //Url de notificações

        $customer = [
            'name' => $customer->Nome, // nome do cliente
            'cpf' => $customer->Documento ? $customer->Documento : $customer->Login, // cpf válido do cliente
            'phone_number' =>  strval($customer->Telefone1) // telefone do cliente
        ];

        $configurations = [ // configurações de juros e mora
            'fine' => 200, // porcentagem de multa
            'interest' => 33 // porcentagem de juros
        ];
        $bankingBillet = [
            'expire_at' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 days')), // data de vencimento do titulo
            'customer' => $customer
        ];

        if ($request->cupom) {
            $cupom = Coupon::where('codigo', $request->cupom)->first();
            if ($cupom->desconto) {
                $bankingBillet = [
                    'expire_at' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 days')), // data de vencimento do titulo
                    'customer' => $customer,
                    // 'discount' =>  [ // configuração de descontos
                    //     'type' => 'currency', // tipo de desconto a ser aplicado
                    //     'value' => intval($cupom->desconto) * 100 // valor de desconto 
                    // ]
                ];
            }
            if ($cupom->descontopercentual) {
                $bankingBillet = [
                    'expire_at' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 days')), // data de vencimento do titulo
                    'customer' => $customer,
                    // 'conditional_discount' => [ // configurações de desconto condicional
                    //     'type' => 'percentage', // seleção do tipo de desconto 
                    //     'value' => intval($cupom->descontopercentual) * 100, // porcentagem de desconto
                    //     'until_date' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 days')) // data máxima para aplicação do desconto
                    // ]
                ];
            }
        }

        $payment = [
            'banking_billet' => $bankingBillet // forma de pagamento (banking_billet = boleto)
        ];

        $body = [
            'items' => $items->all(),
            'shippings' => $shippings,
            'metadata' => $metadata,
            'payment' => $payment
        ];


        try {
            $api = new Gerencianet($options);
            $pay_charge = $api->oneStep([], $body);
            return $pay_charge;
        } catch (\Gerencianet\Exception\GerencianetException $e) {
            return $e->message;
        } catch (AuthorizationException $e) {
            return $e->getMessage();
        }
    }

    public function resendBoleto($charge_id, $email)
    {

        $options = [
            'client_id' => $this->clienteIdDev,
            'client_secret' => $this->clientSecretDev,
            'sandbox' => true // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];

        // $charge_id refere-se ao ID da transação ("charge_id")
        $params = [
            'id' => $charge_id
        ];

        $body = [
            'email' => $email
        ];

        try {
            $api = new \Gerencianet\Gerencianet($options);
            return $api->resendBillet($params, $body);
        } catch (GerencianetException $e) {
            return $e->message;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBoletoStatus($chargeId)
    {
        $options = [
            'client_id' => $this->clienteIdDev,
            'client_secret' => $this->clientSecretDev,
            'sandbox' => true // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];

        $params = [
            'id' => intval($chargeId) // $charge_id refere-se ao ID da transação ("charge_id")
        ];
        try {
            $api = new \Gerencianet\Gerencianet($options);
            return $api->detailCharge($params, []);
        } catch (\Gerencianet\Exception\GerencianetException $e) {
            return $e->message;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function cancelBoleto($chargeId)
    {
        $options = [
            'client_id' => $this->clienteIdDev,
            'client_secret' => $this->clientSecretDev,
            'sandbox' => true  // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];
        // $charge_id refere-se ao ID da transação ("charge_id")
        $params = [
            'id' => $chargeId
        ];
        try {
            $api = new \Gerencianet\Gerencianet($options);
            return $api->cancelCharge($params, []);
        } catch (\Gerencianet\Exception\GerencianetException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }


}
