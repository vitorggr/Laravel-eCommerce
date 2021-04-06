<?php

namespace App\Shop\Attributes;

use App\Shop\AttributeValues\AttributeValue;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'tblcaracteristica';

    protected $fillable = [
        'descricao'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
