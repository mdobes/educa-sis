<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Vynucení příhlášení
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request){
        $offset = ($request->get("offset") ?? 0);
        $limit = ($request->get("limit") ?? 10);

        $rows = User::where("name", "like", "%" . $request->get("search") . "%")->orderBy("name", "asc")->skip($offset)->take($limit)->get();
        $totalNotFiltered = count($rows);
        $total =  User::count();
        return compact("total", "totalNotFiltered", "rows");
    }

    public function index(){
        return view("users.index");
    }

    public function edit(User $id){
        return view("users.edit", compact("id"));
    }

    public function update(UpdateUserRequest $request)
    {

        $dbUser = User::where("id", $request->post("id"))->firstOrFail();
        $adUser = \LdapRecord\Models\ActiveDirectory\User::findByGuid($dbUser->guid);
        $adUser->update(['pwdlastset' => 0]);
        return redirect()->route("users.index");
    }
}
