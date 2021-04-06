<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Color extends Model
{

    protected $table = 'tblcor';

    protected $fillable = [
        'id',
        'descricao',
        'hexadecimal'
    ];

    public function productColor(){
        return $this->hasMany(ProductColor::class);
    }

}