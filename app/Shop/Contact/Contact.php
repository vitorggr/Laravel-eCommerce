<?php

namespace App\Shop\Contact;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{   
    Use SoftDeletes;

    protected $table = "tblcontato";

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'email',
        'mensagem'
    ];
    
}
