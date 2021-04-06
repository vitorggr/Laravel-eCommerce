<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;

class ProductPromotion extends Model
{
    protected $table = 'tblpromocaoproduto';

    protected $fillable = [
        'id',
        'idpromocao',
        'idproduto'
    ];

}
