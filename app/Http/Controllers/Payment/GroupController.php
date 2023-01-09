<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{

    /**
     * Vynucení příhlášení
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ukáže seznam sekupin
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $username = $user->username;
        $showGrouping = true;

        $data = Group::where("author", "=", $username)->orderBy("created_at", "desc")->paginate(15);
        return view("payments.group", compact("data", "user", "username", "showGrouping"));
    }

    public function destroy($id)
    {
        //
    }
}
