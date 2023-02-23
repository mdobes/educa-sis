<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $total =  User::where("name", "like", "%" . $request->get("search") . "%")->count();
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
        $permission = Auth::user()->permission;
        $dbUser = User::where("id", $request->post("id"))->firstOrFail();
        if ($permission !== "student") {
            if ($permission == "teacher" && $dbUser->permission !== "teacher" && $dbUser->permission !== "admin" || $permission == "admin") {
                if ($request->post("noPassword") == "true") {
                    $adUser = \LdapRecord\Models\ActiveDirectory\User::findByGuid($dbUser->guid);
                    $str = Str::random(5);
                    $adUser->update(['pwdlastset' => 0]);
                    $adUser->unicodepwd = $str;
                    return redirect()->route("users.index")->withErrors(['msg' => "Uživateli $dbUser->username bylo změneno heslo na $str"]);
                } else {
                    return redirect()->back()->withErrors(['msg' => 'Uživatele nelze změnit.']);
                }
            }else{
                return redirect()->back()->withErrors(['msg' => 'Uživatel s oprávněním učitel nemůže upravovat uživatele s oprávněním učitel nebo administrátor.']);
            }
        }else{
            return redirect()->back()->withErrors(['msg' => 'Student nemůže upravovat uživatele.']);

        }
    }
}
