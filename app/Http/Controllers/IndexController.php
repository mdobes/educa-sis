<?php

namespace App\Http\Controllers;

use App\Ldap\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Granam\CzechVocative\CzechName;
use Illuminate\Support\Str;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class IndexController extends Controller
{

    /**
     * Vynucení příhlášení
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $name = new CzechName();
        $vocativ = $name->vocative( Str::before(Auth::user()->name, " "));
        return view("index", compact("vocativ"));
    }


    public function giveStudentRole(){
        Permission::findOrCreate("admin");


        /*$user = (new User)->inside('OU=Teachers,OU=School,DC=eduka,DC=local');

        $pwdtxt = "Education2022";
        $newPassword = '"' . $pwdtxt . '"';

        $newPass = iconv( 'UTF-8', 'UTF-16LE', $newPassword );

        $user->cn = 'Aleš Medek';
        $user->unicodePwd = $newPass;
        $user->samaccountname = 'ales.medek';
        $user->userPrincipalName = 'ales.medek@eduka.local';

        $user->save();

        $user->refresh();

        $user->userAccountControl = 512;

        try {
            $user->save();
        } catch (\LdapRecord\LdapRecordException $e) {
            dd($e);
        }*/

        $users = Auth::user()->givePermissionTo('admin');
        return redirect()->route("index");
    }
}
