<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empresa extends Authenticatable
{

    protected $table = "tblempresa";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'razao',
        'inscricaomunicipal',
        'numero_registro',
        'fantasia',
        'documento',
        'telefone1',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'email',
        'obs'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'senhalogin'
    ];

    protected $username = "login";

    protected $email = "login";

    protected $password = "senhalogin";

    public function getAuthPassword()
    {
        return $this->senhalogin;
    }

    public $timestamps = false;

}
