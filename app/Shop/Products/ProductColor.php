<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductColor extends Model
{

    protected $table = 'tblprodutocor';

    protected $fillable = [
        'id',
        'idproduto',
        'idcor'
    ];

    public function color(){
        return $this->belongsToMany(Color::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class);
    }

}