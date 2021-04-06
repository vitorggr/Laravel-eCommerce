<?php

namespace App\Shop\PaymentMethods\Cielo;

use Cielo\API30\Merchant;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Request\CieloRequestException;

use Illuminate\Http\Request;
use App\Shop\Carts\Repositories\CartRepository;
use App\Shop\Carts\ShoppingCart;
use App\Shop\Customers\Customer;
use Psr\Log\LoggerInterface;

use Inacho\CreditCard as ValidateCard;

/**
 * Class CieloRepository
 * @package App\Shop\PaymentMethods\Cielo
 * @codeCoverageIgnore
 *
 * @todo Make a test for this
 */
class CieloRepository
{

    private $environment;
    private $merchant;
    private $card_brand;

    public function __construct()
    {
        // Configure o ambiente
        $this->environment = Environment::sandbox();

        // Configure seu merchant
        $this->merchant = new Merchant(env('CIELO_MERCHANT_ID', 'MERCHANT ID'), env('CIELO_MERCHANT_KEY', 'MERCHANT KEY'));
    }

    public function execute(Request $request)
    {
        $cartRepo = new CartRepository(new ShoppingCart());
        $card = (new ValidateCard())->validCreditCard($request->input('card_number'));

        switch ($card['type']) {
            case 'visa':
                $this->card_brand = CreditCard::VISA;
                break;
            case 'mastercard':
                $this->card_brand = CreditCard::MASTERCARD;
                break;
            case 'amex':
                $this->card_brand = CreditCard::AMEX;
                break;
            case 'dinersclub':
                $this->card_brand = CreditCard::DINERS;
                break;
            case 'discover':
                $this->card_brand = CreditCard::DISCOVER;
                break;
            case 'jcb':
                $this->card_brand = CreditCard::JCB;
                break;
            default:
                break;
        }

        // Crie uma instância de Sale e informando o ID do pedido na loja
        $sale = new Sale('123');

        // Crie uma instância de Customer informando o nome do cliente
        $sale->customer(auth()->user()->Nome);

        // Crie uma instância de Payment informando o valor do pagamento
        $payment = $sale->payment(intval($cartRepo->getTotal()), $request->input('installments'));

        // Crie uma instância de Credit Card utilizando os dados de teste
        // esses dados estão disponíveis no manual de integração
        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
            ->creditCard($request->input('card_cvv'), $this->card_brand)
            ->setExpirationDate($request->input('card_expiration'))
            ->setCardNumber($request->input('card_number'))
            ->setHolder($request->input('card_holder'));

        // Crie o pagamento na Cielo

        try {
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale = (new CieloEcommerce($this->merchant, $this->environment))->createSale($sale);
            
            // Com a venda criada na Cielo, já temos o ID do pagamento, TID e demais
            // dados retornados pela Cielo
            $paymentId = $sale->getPayment()->getPaymentId();
            
            // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
            $sale = (new CieloEcommerce($this->merchant, $this->environment))->getSale($paymentId);
            $sale = (new CieloEcommerce($this->merchant, $this->environment))->captureSale($paymentId, 15700, 0);
            dd($sale);
        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            dd($e->getCieloError());
            $error = $e->getCieloError();
        }
    }
}
