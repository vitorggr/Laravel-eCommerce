<?php

namespace App\Shop\Categories;

use App\Shop\Products\Product;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
 
    protected $table = 'tblcategoria';

    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'descricao',
        'site',
        'idempresa',
        'idcategoria_loja',
        'slug',
        'idcategoria',
        'banner',
        'ativo'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function productsCat($id)
    {
        return Product::where('idcategoria',$id)->get();
    }
}
