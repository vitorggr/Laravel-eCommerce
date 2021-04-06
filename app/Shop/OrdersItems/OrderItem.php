<?php

namespace App\Shop\OrdersItems;

use App\Shop\Addresses\Address;
use App\Shop\Couriers\Courier;
use App\Shop\Customers\Customer;
use App\Shop\Orders\Order;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class OrderItem extends Model
{
    // use SearchableTrait;

    protected $table = 'tblpedidoitemproduto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qtd',
        'valor',
        'subtotal',
        'idgrade',
        'idproduto', // @deprecated
        'idpedido'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public $timestamps = false;
    
    public function order(){
        return $this->belongsToMany(Order::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class);
    }

}
