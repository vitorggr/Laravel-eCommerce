<?php

namespace App\Shop\Couriers;

use App\Shop\Orders\Order;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $table = 'tblmotorista';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'url',
        'is_free',
        'cost',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
