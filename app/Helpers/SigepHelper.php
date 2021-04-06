<?php

namespace App\Helpers;

use App\Shop\Addresses\Address;
use App\Providers\SigepServiceProvider;
use App\Shop\Customers\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpSigep\Model\Dimensao;
use PhpSigep\Model\VerificaDisponibilidadeServico;
use PhpSigep\Services\Real\VerificarStatusCartaoPostagem;
use PhpSigep\Services\SoapClient\Real;

class SigepHelper
{
    private $encomenda,$altura,$largura,$comprimento,$peso;

    public function  getStatusCartaoPostagem()
    {
        $sigepServiceProvider = new SigepServiceProvider();
        $sigep = new Real();
        return $sigep->verificarStatusCartaoPostagem(
            $sigepServiceProvider->getAccess()->getCartaoPostagem(),
            $sigepServiceProvider->getAccess()->getUsuario(),
            $sigepServiceProvider->getAccess()->getSenha()
        );
    }

    public function getCliente()
    {
        $sigepServiceProvider = new SigepServiceProvider();

        $phpSigep = new Real();
        $result = $phpSigep->buscaCliente($sigepServiceProvider->getAccess());

        if (!$result->hasError()) {
            /** @var $buscaClienteResult \PhpSigep\Model\BuscaClienteResult */
            $buscaClienteResult = $result->getResult();

            // Anula as chancelas antes de imprimir o resultado, porque as chancelas não estão é liguagem humana
            $servicos = $buscaClienteResult->getContratos()->cartoesPostagem->servicos;
            foreach ($servicos as &$servico) {
                $servico->servicoSigep->chancela->chancela = 'Chancelas anulada via código.';
            }
        }

        return $result;
    }

    //public funcion getCep(){
    // 
    // }

    public function getDisponibilidadeServico($cepOrigem)
    {
        $sigepServiceProvider = new SigepServiceProvider();

        $disponibilidade = new VerificaDisponibilidadeServico();
        $disponibilidade->setAccessData($sigepServiceProvider->getAccess());
        $disponibilidade->setCepOrigem($cepOrigem);
        $disponibilidade->setCepDestino(Address::select('cep')->where('id', request()->billing_address)->get()->first());
        $disponibilidade->setServicos(\PhpSigep\Model\ServicoDePostagem::getAll());
        $phpSigep = new Real();
        $result = $phpSigep->verificaDisponibilidadeServico($disponibilidade)->getResult();
        return $result->getDisponivel();
    }

    public function getEtiquetas()
    {

        $sigepServiceProvider = new SigepServiceProvider();
        $usuario = $sigepServiceProvider->getAccess()->getUsuario();
        $senha = $sigepServiceProvider->getAccess()->getSenha();
        $cnpjEmpresa = $sigepServiceProvider->getAccess()->getCnpjEmpresa();

        $accessData = new \PhpSigep\Model\AccessData();
        $accessData->setUsuario($usuario);
        $accessData->setSenha($senha);
        $accessData->setCnpjEmpresa($cnpjEmpresa);

        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas(1); // BUSCAR PELA QUANTIDADE DE PRODUTOS
        $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA);
        $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA);
        $params->setAccessData($accessData);

        $phpSigep = new Real();
        return $phpSigep->solicitaEtiquetas($params);
    }

    public function getDvEtiquetas($etiquetaSemDv)
    {
        $sigepServiceProvider = new SigepServiceProvider();

        $etiquetas = array();

        $params = new \PhpSigep\Model\GeraDigitoVerificadorEtiquetas();
        $params->setAccessData($sigepServiceProvider->getAccess());
        $params->setEtiquetas($etiquetaSemDv);

        $phpSigep = new \PhpSigep\Services\SoapClient\Real();
        return $phpSigep->geraDigitoVerificadorEtiquetas($params);
    }

    public function createPlp(Collection $cartItems)
    {
        foreach ($cartItems as $item) {
            $grade = $item->product->grade->first();
            $this->altura += ($grade['altura'] * $item->qty);
            $this->comprimento += ($grade['comprimento'] * $item->qty);
            $this->largura += ($grade['largura'] * $item->qty);
            $this->peso += ($grade['peso'] * $item->qty);
        }

        $dimensao = new \PhpSigep\Model\Dimensao();
        $dimensao->setTipo(Dimensao::TIPO_PACOTE_CAIXA);
        $dimensao->setAltura($this->altura); // em centímetros
        $dimensao->setComprimento($this->comprimento); // em centímetros
        $dimensao->setLargura($this->largura); // em centímetros


        // if (auth()->user()) {
            $endereco = Address::where('cliente_id', auth()->user()->Id)->first();
            $destinatario = new \PhpSigep\Model\Destinatario();
            $destinatario->setNome(Customer::where('id', $endereco->cliente_id)->first()->Nome);
            $destinatario->setLogradouro($endereco->endereco);
            $destinatario->setNumero($endereco->complemento);
            $destinatario->setComplemento($endereco->complemento);

            $destino = new \PhpSigep\Model\DestinoNacional();
            $destino->setBairro($endereco->endereco);
            $destino->setCep($endereco->cep);
            $destino->setCidade($endereco->cidade);
            $destino->setUf($endereco->estado);
        // } else {
        //     $destinatario = new \PhpSigep\Model\Destinatario();
        //     $destinatario->setNome('Google Belo Horizonte');
        //     $destinatario->setLogradouro('Av. Bias Fortes');
        //     $destinatario->setNumero('382');
        //     $destinatario->setComplemento('6º andar');

        //     $destino = new \PhpSigep\Model\DestinoNacional();
        //     $destino->setBairro('Lourdes');
        //     $destino->setCep('30170-010');
        //     $destino->setCidade('Belo Horizonte');
        //     $destino->setUf('MG');
        // }

        // Estamos criando uma etique falsa, mas em um ambiente real voçê deve usar o método
        // {@link \PhpSigep\Services\SoapClient\Real::solicitaEtiquetas() } para gerar o número das etiquetas
        $etiqueta = new \PhpSigep\Model\Etiqueta();
        $etiqueta = $this->getDvEtiquetas($this->getEtiquetas()->getResult())->getResult()[0];

        $servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
        $servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
        // Se não tiver valor declarado informar 0 (zero)
        $servicoAdicional->setValorDeclarado(0);

        $this->encomenda = new \PhpSigep\Model\ObjetoPostal();
        $this->encomenda->setServicosAdicionais(array($servicoAdicional));
        $this->encomenda->setDestinatario($destinatario);
        $this->encomenda->setDestino($destino);
        $this->encomenda->setDimensao($dimensao);
        $this->encomenda->setEtiqueta($etiqueta);
        $this->encomenda->setPeso($this->peso); // 500 gramas
        $this->encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_10));
        // ***  FIM DOS DADOS DA ENCOMENDA QUE SERÁ DESPACHADA *** //

        // *** DADOS DO REMETENTE *** //
        $remetente = DB::table('tblempresa')->where('id', 13)->first();
        $this->remetente = new \PhpSigep\Model\Remetente();
        $this->remetente->setNome($remetente->razao);
        $this->remetente->setLogradouro($remetente->endereco);
        $this->remetente->setNumero($remetente->numero);
        $this->remetente->setComplemento($remetente->complemento);
        $this->remetente->setBairro($remetente->bairro);
        $this->remetente->setCep($remetente->cep);
        $this->remetente->setUf($remetente->uf);
        $this->remetente->setCidade($remetente->cidade);
        // *** FIM DOS DADOS DO REMETENTE *** //

    }

    public function getPlp(Collection $cartItems)
    {
        $sigepServiceProvider = new SigepServiceProvider();
        $this->createPlp($cartItems);
        $plp = new \PhpSigep\Model\PreListaDePostagem();
        $plp->setAccessData($sigepServiceProvider->getAccess());
        $plp->setEncomendas(array($this->encomenda));
        $plp->setRemetente($this->remetente);
        return $plp;
    }

    public function getPlpVariosServicos(Collection $cartItems)
    {
        $phpSigep = new Real();
        return $phpSigep->fechaPlpVariosServicos($this->getPlp($cartItems));
    }

    public function getRastreio()
    {
        $sigepServiceProvider = new SigepServiceProvider();
        $etiquetasFromQueryRaw = trim((isset($_GET['etiquetas']) ? $_GET['etiquetas'] : ''));
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
            $accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
            $accessDataDeHomologacao->setUsuario($sigepServiceProvider->getAccess()->getUsuario()); // Usuário e senha para teste passado no manual
            $accessDataDeHomologacao->setSenha($sigepServiceProvider->getAccess()->getSenha());

            $params = new \PhpSigep\Model\RastrearObjeto();
            $params->setAccessData($accessDataDeHomologacao);
            $params->setEtiquetas($etiquetas);

            $phpSigep = new \PhpSigep\Services\SoapClient\Real();
            return $phpSigep->rastrearObjeto($params);
        }
    }


    public function getXmlPlp($xmlPlp){
        new SigepServiceProvider();
        $sigep = new Real();
        $response = $sigep->SolicitaXmlPlp($xmlPlp);
        return $response;
    }
    // solicita XmlPlp

    // bloquearObjeto

}
