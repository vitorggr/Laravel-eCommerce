<?php

namespace App\Http\Controllers\Auth;

use App\Shop\Customers\Customer;
use App\Http\Controllers\Controller;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Customers\Requests\CreateCustomerRequest;
use App\Shop\Customers\Requests\RegisterCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/accounts';

    private $customerRepo;

    /**
     * Create a new controller instance.
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->middleware('guest');
        $this->customerRepo = $customerRepository;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Customer
     */
    protected function create($request)
    {

        $request['login'] = str_replace([".","-","/"],"",$request['login']);
        $checkUser = Customer::where('login',$request['login'])->first();

        if(!$checkUser){
            Customer::insert([
                "Nome"=>$request['Nome'],
                "Login"=>$request['login'],
                "Documento"=>$request['login'],
                "senha"=>$request['senha'],
                "Status"=>1,
                'idempresa'=>0
            ]);

            return redirect()->route('home');
        }
        exit;

    }

    /**
     * @param RegisterCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->merge([
            'login'=>str_replace([".","-","/"],"",$request->login)
        ]);

        $checkUser = Customer::where('login',$request->login)->first();

        if(!$checkUser){
            
            $id = Customer::insertGetId([
                "Nome"=>$request->Nome,
                "login"=>$request->login,
                "senha"=>$request->senha,
                "Status"=>1,
                "idempresa"=>0
            ]);

            $user = Customer::where([
                ["login",$request->login],
                ["senha",$request->senha]
            ])->select('id','login','senha')->first();

            Auth::login($user);

            return redirect()->route('home');
        }

        return redirect('register')
            ->withErrors(['Usuário já cadastrado no sistema, favor efetuar o login!'])
            ->withInput();

        //return redirect()->route('accounts');
    }
}
