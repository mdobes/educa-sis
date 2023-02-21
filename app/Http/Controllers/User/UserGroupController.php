<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserGroup\StoreUserGroupRequest;
use App\Http\Requests\UserGroup\UpdateUserGroupRequest;
use App\Models\User;
use App\Models\UserGroup;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserGroupController extends Controller
{

    public function index(){
        return view("usergroups.index");
    }

    public function search(Request $request){
        $offset = ($request->get("offset") ?? 0);
        $limit = ($request->get("limit") ?? 10);

        $rows = UserGroup::where("name", "like", "%" . $request->get("search") . "%")->orderBy("name", "asc")->skip($offset)->take($limit)->get();
        $totalNotFiltered = count($rows);
        $total =  UserGroup::where("name", "like", "%" . $request->get("search") . "%")->count();
        return compact("total", "totalNotFiltered", "rows");
    }

    public function edit(UserGroup $id){
        return view("usergroups.edit", compact("id"));
    }

    public function create(){
        return view("usergroups.create");
    }

    public function update(UpdateUserGroupRequest $request)
    {
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            foreach(explode(",", $request->post("users")) as $username){
                $user = User::where("username", $username)->first();
                if(!$user) return redirect()->back()->withErrors(['msg' => 'Uživatel s uživatelským jménem ' . $username . ' neexistuje.']);
            }
            UserGroup::whereId($request->post("id"))->update(["users" => $request->post("users")]);
            return redirect()->route("usergroup.index");
        }else{
            return redirect()->back()->withErrors(['msg' => 'Nemáte dostatečná oprávnění k úpravě skupin.']);

        }
    }

    public function store(StoreUserGroupRequest $request)
    {
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            foreach(explode(",", $request->post("users")) as $username){
                $user = User::where("username", $username)->first();
                if(!$user) return redirect()->back()->withErrors(['msg' => 'Uživatel s uživatelským jménem ' . $username . ' neexistuje.'])->withInput($request->all());
            }
            UserGroup::create($request->all());
            return redirect()->route("usergroup.index");
        }else{
            return redirect()->back()->withErrors(['msg' => 'Nemáte dostatečná oprávnění k vytváření skupin.'])->withInput($request->all());

        }
    }

    public function microsoftImportSearch(){
        $client = new Client();
        $response = $client->get('https://graph.microsoft.com/v1.0/groups?$search="displayName:m2019"', [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer"),
                'ConsistencyLevel' => 'eventual'
            ],
        ]);

        dd(json_decode($response->getBody()));
    }

    public function microsoftImport(String $id){
        $client = new Client();
        //dd(Cache::get("azureBearer"));
        $response = $client->get('https://graph.microsoft.com/v1.0/groups/' . $id . '/members', [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer")
            ],
        ]);

        dd(json_decode($response->getBody()));
    }

    public function microsoftUser(String $id){
        $client = new Client();
        //dd(Cache::get("azureBearer"));
        $response = $client->get('https://graph.microsoft.com/v1.0/users/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer")
            ],
        ]);

        dd(json_decode($response->getBody()));
    }
}
