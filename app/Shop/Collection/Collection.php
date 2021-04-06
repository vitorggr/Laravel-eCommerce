<?php

namespace App\Shop\Collection;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'tblcolecao';    

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'ativa',
        'datainicio',
        'datafim',
        'id_empresa'
    ];

    public function productCollection(){
        return $this->hasMany(ProductCollection::class);
    }
}
