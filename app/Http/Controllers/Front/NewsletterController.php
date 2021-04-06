<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Newsletter\Newsletter;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $newsletter = new Newsletter();
        $newsletter->email = $request->email;
        $newsletter->dt_criacao = date("Y-m-d");
        $newsletter->save();
        request()->session()->flash('message', 'E-mail cadastrado com sucesso');
        return redirect()->action('Front\HomeController@index');
    }
}
