<?php

namespace App\Shop\Promotions;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'tblpromocao';

    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'bannerlateral',
        'descricao',
        'bannercartao',
        'titulo',
        'subtitulo',
        'ativo',
        'datainicio',
        'data fim',
        'descontounitario',
        'descontopercentual'
    ];



}
