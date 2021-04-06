<?php

namespace App\Shop\Orders;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class OrderCharge extends Model
{
    use SearchableTrait;
    
    public $timestamps = false;

    protected $table = 'tblpedidocobranca';
}