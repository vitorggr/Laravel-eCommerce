<?php

namespace App\Shop\Shipping\Contracts;

interface ZipCodeInterface
{
    /**
     * Encontrar endereço por CEP.
     *
     * @param  string $zipcode
     *
     * @return array
     */
    public function find($zipcode);
}
