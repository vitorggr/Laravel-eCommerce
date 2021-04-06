<?php

namespace App\Http\Controllers\Admin;

use App\Shop\Admins\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Empresa;
use App\Shop\Customers\Customer;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    /**
     * Shows the admin login form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (auth()->guard('employee')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin.login');
    }

    /**
     * Login the employee
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function login(LoginRequest $request)
    // {
    //     $this->validateLogin($request);

    //     // If the class is using the ThrottlesLogins trait, we can automatically throttle
    //     // the login attempts for this application. We'll key this by the username and
    //     // the IP address of the client making these requests into this application.
    //     if ($this->hasTooManyLoginAttempts($request)) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }

    //     $details = $request->only('email', 'password');
    //     $details['status'] = 1;
    //     if (auth()->guard('employee')->attempt($details)) {
    //         return $this->sendLoginResponse($request);
    //     }

    //     // If the login attempt was unsuccessful we will increment the number of attempts
    //     // to login and redirect the user back to the login form. Of course, when this
    //     // user surpasses their maximum number of attempts they will get locked out.
    //     $this->incrementLoginAttempts($request);

    //     return $this->sendFailedLoginResponse($request);
    // }

    public function login(Request $request)
    {

        $request->merge([
            "login"=>str_replace([".","-","/"],"",$request->login)
        ]);

        $user = Customer::where([
            ["Login",$request->login],
            ["senha",$request->senhalogin]
        ])->first();

        if($user)
        {
            Auth::login($user);
            return redirect()->route('admin.dashboard', $user);
        }else{
            return redirect('admin/login')
                ->with('error','CPF/CNPJ ou Senha inválidos')
                ->withInput();
        }
          
    }

}
