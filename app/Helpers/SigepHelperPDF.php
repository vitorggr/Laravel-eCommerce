<?php

namespace App\Helpers;

use App\Providers\CorreioEnvironmentServiceProvider;
use App\Providers\SigepServiceProvider;

class SigepHelperPDF
{
    private $sigepHelper;

    public function printAvisoRecebimento()
    {
        $this->sigepHelper = new SigepHelper();
        $pdf = new \PhpSigep\Pdf\AvisoRecebimento($this->sigepHelper->getPlp());
        return $pdf->render();
    }

    public function printEtiquetas($params)
    {
        new SigepServiceProvider();
        $this->sigepHelper = new SigepHelper();

        // Logo da empresa remetente, estático até configuração
        $logoFile = __DIR__ . '/logo-etiqueta-2016.png';

        $pdf = new \PhpSigep\Pdf\CartaoDePostagem2018($this->sigepHelper->getPlp($params), time(), $logoFile, array());
        return $pdf->render();
    }

    public function printEtiquetasA4()
    {
        new SigepServiceProvider();
        $this->sigepHelper = new SigepHelper();

        // Logo da empresa remetente
        $logoFile = __DIR__ . '/logo-etiqueta-2016.png';

        //Parametro opcional indica qual layout utilizar para a chancela. Ex.: CartaoDePostagem::TYPE_CHANCELA_CARTA, CartaoDePostagem::TYPE_CHANCELA_CARTA_2016
        $layoutChancela = array(); //array(\PhpSigep\Pdf\CartaoDePostagem2016::TYPE_CHANCELA_SEDEX_2016);

        $pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($this->sigepHelper->getPlp(), time(), $logoFile, $layoutChancela);

        $fileName = tempnam(sys_get_temp_dir(), 'phpsigep') . 'cartao_postagem_A4.pdf';
        $pdf->render('F', $fileName);

        unset($pdf);
        $pdf = new \PhpSigep\PDF\ImprovedFPDF('P', 'mm', 'Letter');
        $pageCount = $pdf->setSourceFile($fileName);

        $pdf->AddPage();
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 16);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i, '/MediaBox');

            $mod = $i % 4;

            switch ($mod) {
                case 0:
                    //A4: 210(x) × 297(y)
                    //Letter: 216 (x) × 279 (y)
                    $pdf->useTemplate($tplIdx, 110, 145, 105, 138, true);

                    if ($i !== $pageCount) {
                        $pdf->AddPage();
                        $pdf->SetFillColor(0, 0, 0);
                        $pdf->SetFont('Arial', 'B', 16);
                    }
                    break;
                case 1:
                    $pdf->useTemplate($tplIdx, 10, 10, 105, 138, true);
                    break;
                case 2:
                    $pdf->useTemplate($tplIdx, 110, 10, 105, 138, true);
                    break;
                case 3:
                    $pdf->useTemplate($tplIdx, 10, 145, 105, 138, true);
                    break;
            }
        }

        // return $pdf->Output('teste.pdf', 'F');
        return $pdf->Output();
    }

    public function printPreListaPostagem($params)
    {
        new SigepServiceProvider();
        $this->sigepHelper = new SigepHelper();
        $pdf  = new \PhpSigep\Pdf\ListaDePostagem($this->sigepHelper->getPlp($params), time());
        return $pdf->render('I');
    }

    public function printChancelas()
    {   
        new SigepServiceProvider();

        $pdf = new \PhpSigep\Pdf\ImprovedFPDF('P', 'mm', array(100, 140));
        $pdf->SetFont('Arial', '', 10);
        $pdf->AddPage();

        $sigepServiceProvider = new SigepServiceProvider();
        $accessData = $sigepServiceProvider->getAccess();

        $carta = new \PhpSigep\Pdf\Chancela\Carta2016(5, 5, 'Layout 2016', $accessData);
        $carta->draw($pdf);

        $carta = new \PhpSigep\Pdf\Chancela\Carta(50, 5, 'Layout Antigo', $accessData);
        $carta->draw($pdf);

        $sedex = new \PhpSigep\Pdf\Chancela\Sedex2016(5, 50, 'Layout 2016', 2, $accessData);
        $sedex->draw($pdf);

        $sedex = new \PhpSigep\Pdf\Chancela\Sedex(50, 50, 'Layout antigo', 2, $accessData);
        $sedex->draw($pdf);

        $pac = new \PhpSigep\Pdf\Chancela\Pac2016(5, 100, 'Layout 2016', $accessData);
        $pac->draw($pdf);

        $pac = new \PhpSigep\Pdf\Chancela\Pac(50, 100, 'Layout antigo', $accessData);
        $pac->draw($pdf);

        $pdf->Output();
    }
}
