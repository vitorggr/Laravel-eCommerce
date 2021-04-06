<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductGrade extends Model
{

    protected $table = 'tblprodutograde';

    protected $fillable = [
        'id',
        'idproduto'
    ];

    public static function productPrice($id)
    {
        return ProductGrade::where('idproduto',$id)->orderBy('valorvenda','ASC')->select('valorvenda')->first();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
