<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;

class LoginController extends Controller
{
    use ListensForLdapBindFailure;

    public function post(LoginRequest $request){

        //dd($request->all());
        //dd(Auth::attempt($request->all()));

        $credentials = [
            'samaccountname' => $request->post("username"),
            'password' => $request->post("password"),
        ];

        $data = [
            'username' => $request->post("username"),
            'password' => $request->post("password"),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $message = "Úspěšně přihlášen.";
            return redirect('/')->with(compact("message"));
        }else{
            $message = "Špatně zadané uživatelské jméno nebo heslo.";
            return view("login", compact("credentials", "message"));
        }
    }

    public function get(){
        return view("login");
    }

}
