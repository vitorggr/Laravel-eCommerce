<?php

namespace App\Providers;

use Error;
use Exception;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;

class PixServiceProvider extends ServiceProvider
{
    private $clienteIdDev = 'Client_Id_47ddba3b9b90c12e704d2684e6510af66fda2167';
    private $clienteSenhaDev = 'Client_Secret_10da097f668dac7bc3e6b13fbbf79f8d6d04f25a';
    private $sandbox = 'https://api-pix-h.gerencianet.com.br';
    private $config;

    public function __construct()
    {
        $this->config = [
            //"certificado" => "./certificado.pem",
            "certificado" => 'C:\Workspace\EasyShop\config\cert.pem',
            "client_id" => $this->clienteIdDev,
            "client_secret" => $this->clienteSenhaDev
        ];

        $autorizacao =  base64_encode($this->config["client_id"] . ":" . $this->config["client_secret"]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->sandbox, // Rota base, desenvolvimento ou produção
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"grant_type": "client_credentials"}',
            CURLOPT_SSLCERT => $this->config["certificado"], // Caminho do certificado
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $autorizacao",
                "Content-Type: application/json"
            ),
        ));

        curl_exec($curl);
        curl_close($curl);
    }

    public function createCharge($cartItems, $shipping)
    {
        $pix = array();

        $total = null;
        foreach ($cartItems as $key => $item) {
            $total += $item->price;
        }

        try {
            $api = new Gerencianet($this->getOptions());
            $params = [];

            $body = [
                "calendario" => [
                    "expiracao" => 3600
                ],
                "devedor" => [
                    "cpf" => auth()->user()->Documento,
                    "nome" => auth()->user()->Nome
                ],
                "valor" => [
                    "original" => strval(floatval($total + $shipping))
                ],
                "chave" => "71cdf9ba-c695-4e3c-b010-abb521a3f1b",
                "solicitacaoPagador" => "Cobrança da Compra Realizada."
            ];

            $pix['payload'] = ($api->pixCreateImmediateCharge($params, $body));

            if ($pix['payload']['txid']) {

                // Gera QRCode
                $params = [
                    'id' => $pix['payload']['loc']['id']
                ];

                $pix['qrcode'] = ($api->pixGenerateQRCode($params));
                $pix['payment'] = "pix";

                // echo 'Detalhes da cobrança:';
                // echo '<pre>' . json_encode($pix, JSON_PRETTY_PRINT) . '</pre>';
                // echo 'QR Code:';
                // echo '<pre>' . json_encode( $pix['qrcode'], JSON_PRETTY_PRINT) . '</pre>';
                // echo 'Imagem:<br />';
                // echo '<img src="' .  $pix['qrcode']['imagemQrcode'] . '" />';

                return $pix;
            }
        } catch (GerencianetException $e) {
            return $e;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCharge($chargeId)
    {
        try {
            $params = ['txid' => $chargeId];
            $api = Gerencianet::getInstance($this->getOptions());
            return $api->pixDetailCharge($params);
        } catch (GerencianetException $e) {
            return $e;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function createDevolution($chargeId)
    {
        try {
            $api = Gerencianet::getInstance($this->getOptions());
            $body = [
                'valor' => $this->getCharge($chargeId)['valor']['original']
            ];
            $params = [
                'e2eId' => $chargeId,
                'id'    => auth()->user()->Id
            ];
            $pix = $api->pixDevolution($params, $body);
            return $pix;
        } catch (GerencianetException $e) {
            return $e;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getOptions()
    {
        $options = [
            "client_id" => $this->clienteIdDev,
            "client_secret" => $this->clienteSenhaDev,
            "sandbox" => true,
            "debug" => false,
            "pix_cert" => 'C:\Workspace\EasyShop\config\cert.pem'
        ];
        return $options;
    }
}
