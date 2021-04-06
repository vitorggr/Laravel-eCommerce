<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stripe\Account;

class ProductReview extends Model
{   
    
    protected $table = 'tblprodutoreview';

    const UPDATED_AT = null;
    const DELETED_AT =  null;
    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idproduto',
        'idcliente',
        'assunto',
        'mensagem',
        'avaliacao',
        'ativo',
        'data'
    ];

    public function produto()
    {
        return $this->belongsTo(Product::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Account::class);
    }

}
