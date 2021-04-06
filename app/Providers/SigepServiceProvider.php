<?php

namespace App\Providers;

use App\Helpers\SigepHelper;
use PhpSigep\Model\AccessData;
use PhpSigep\Model\Diretoria;
use PhpSigep\Services\SoapClient\Real;

class SigepServiceProvider extends SigepHelper
{
    private $accessDataParaAmbienteDeHomologacao;
    private $config;

    public function __construct(){
        $this->accessDataParaAmbienteDeHomologacao = new AccessData();
        $this->accessDataParaAmbienteDeHomologacao->setUsuario('SUPERFIVE');
        $this->accessDataParaAmbienteDeHomologacao->setSenha('8p36nz');
        $this->accessDataParaAmbienteDeHomologacao->setCodAdministrativo('18160492');
        $this->accessDataParaAmbienteDeHomologacao->setNumeroContrato('9912441408');
        $this->accessDataParaAmbienteDeHomologacao->setCartaoPostagem('0074114255');
        $this->accessDataParaAmbienteDeHomologacao->setCnpjEmpresa('08692750000183');
        // $this->accessDataParaAmbienteDeHomologacao->setUsuario('sigep');
        // $this->accessDataParaAmbienteDeHomologacao->setSenha('n5f9t8');
        // $this->accessDataParaAmbienteDeHomologacao->setCodAdministrativo('17000190');
        // $this->accessDataParaAmbienteDeHomologacao->setNumeroContrato('9992157880');
        // $this->accessDataParaAmbienteDeHomologacao->setCartaoPostagem('0067599079');
        // $this->accessDataParaAmbienteDeHomologacao->setCnpjEmpresa('34028316000103');
        $this->accessDataParaAmbienteDeHomologacao->setDiretoria(new Diretoria(Diretoria::DIRETORIA_DR_BRASILIA));

        $this->config = new \PhpSigep\Config();
        $this->config->setAccessData($this->accessDataParaAmbienteDeHomologacao);
        $this->config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
        $this->config->setCacheOptions(
            array(
                'storageOptions' => array(
                    // Qualquer valor setado neste atributo será mesclado ao atributos das classes 
                    // "\PhpSigep\Cache\Storage\Adapter\AdapterOptions" e "\PhpSigep\Cache\Storage\Adapter\FileSystemOptions".
                    // Por tanto as chaves devem ser o nome de um dos atributos dessas classes.
                    'enabled' => true,
                    'ttl' => 60*60*24*7,// "time to live" de 10 segundos
                    'cacheDir' => sys_get_temp_dir(), // Opcional. Quando não inforado é usado o valor retornado de "sys_get_temp_dir()"
                ),
            )
        );
        
        \PhpSigep\Bootstrap::start($this->config);
    }

    public function getAccess(){
        return $this->accessDataParaAmbienteDeHomologacao;
    }

}


