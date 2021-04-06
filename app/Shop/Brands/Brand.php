<?php

namespace App\Shop\Brands;

use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'tblmarca';

    protected $fillable = ['descricao'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
