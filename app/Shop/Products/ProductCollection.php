<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;

class ProductCollection extends Model
{
    protected $table = 'tblprodutocolecao';

    protected $fillable = [
        'id',
        'idproduto',
        'idcolecao'
    ];

    public function colecao(){
        return $this->belongsToMany(Collection::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class);
    }
}
