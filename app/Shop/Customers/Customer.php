<?php

namespace App\Shop\Customers;

use App\Shop\Addresses\Address;
use App\Shop\Orders\Order;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Laravel\Cashier\Billable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Customer extends Authenticatable
{
    use Notifiable, SoftDeletes, SearchableTrait;

    protected $table = 'tblcliente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Nome',
        'Codigo',
        'nome',
        'aniversario',
        'Documento',
        'Email',
        'senha',
        'idempresa'
    ];

    protected $username = "login";

    protected $email = "login";

    protected $password = "senha";

    protected $primaryKey = 'Id';

    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    //     'senha'
    // ];

    // protected $dates = ['deleted_at'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    // protected $searchable = [
    //     'columns' => [
    //         'customers.name' => 10,
    //         'customers.email' => 5
    //     ]
    // ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class)->whereStatus(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @param $term
     *
     * @return mixed
     */
    public function searchCustomer($term)
    {
        return self::search($term);
    }
}
