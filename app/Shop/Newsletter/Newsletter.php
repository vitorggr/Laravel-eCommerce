<?php
namespace App\Shop\Newsletter; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model {

    use SoftDeletes;
    protected $table = "tblnewsletter";
    public $timestamps = false;

    protected $fillable = [
        'email',
        'dt_criacao'
    ];

}