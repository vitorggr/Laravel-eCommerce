<?php

namespace App\Shop\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model{
    
    
    protected $table = 'tblcupom';
    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'codigo',
        'ativo',
        'tipo',
        'desconto',
        'idempresa',
        'descontopercentual',
        'limite',
        'validade'
    ];
}

?>