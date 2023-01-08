<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Granam\CzechVocative\CzechName;
use Illuminate\Support\Str;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class IndexController extends Controller
{

    public function index(){
        $name = new CzechName();
        $vocativ = $name->vocative( Str::before(Auth::user()->name, " "));
        return view("index", compact("vocativ"));
    }


    public function giveStudentRole(){
        $users = Auth::user()->assignRole('teachers');
    }
}
