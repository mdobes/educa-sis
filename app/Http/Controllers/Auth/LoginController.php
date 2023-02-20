<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;

class LoginController extends Controller
{
    use ListensForLdapBindFailure;

    /**
     * Vynucení příhlášení
     */

    public function post(LoginRequest $request){

        $credentials = [
            'samaccountname' => $request->post("username"),
            'password' => $request->post("password"),
        ];

        $remember = $request->post("remember");

        if (Auth::attempt($credentials, $remember) ) {
            $user = Auth::user();

            if($user->permission == "teacher" || $user->permission == "admin") {
                return redirect()->route("index");
            }else{
                Session::flush();
                Auth::logout();

                return redirect()->back()->withErrors(['msg' => 'Uživatel nemá dostatečné oprávnění k přihlášení.']);
            }
        }else{
            return redirect()->back()->withErrors(['msg' => 'Špatně zadané uživatelské jméno nebo heslo.']);

        }
    }

    public function get(){
        if(Auth::user()) return redirect('/');
        else return view("login");
    }

}
