<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = "tblcarrossel";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idempresa',
        'imagem',
        'imagemthumb',
        'imagemmobile',
        'imagemmobilethumb',
        'ativo',
        'titulo',
        'subtitulo',
        'idcolecao',
        'idcategoria',
        'link',
        'linkmobile',
        'ordem'
    ];

   




}
