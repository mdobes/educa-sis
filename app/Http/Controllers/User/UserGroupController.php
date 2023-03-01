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
        $permission = Auth::user()->permission;
        if ($permission == "admin"){
            return view("usergroups.index");
        }else{
            return abort(403);
        }
    }

    public function search(Request $request){
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            $offset = ($request->get("offset") ?? 0);
            $limit = ($request->get("limit") ?? 10);

            $rows = UserGroup::where("name", "like", "%" . $request->get("search") . "%")->orderBy("name", "asc")->skip($offset)->take($limit)->get();
            $totalNotFiltered = count($rows);
            $total = UserGroup::where("name", "like", "%" . $request->get("search") . "%")->count();
            return compact("total", "totalNotFiltered", "rows");
        }else{
            return abort(403);
        }
    }

    public function edit(UserGroup $id){
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            return view("usergroups.edit", compact("id"));
        }else{
            return abort(403);
        }
    }

    public function create(){
        return view("usergroups.create");
    }

    public function update(UpdateUserGroupRequest $request)
    {
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            $permission = Auth::user()->permission;
            if ($permission == "admin") {
                foreach (explode(",", $request->post("users")) as $username) {
                    $user = User::where("username", $username)->first();
                    if (!$user) return redirect()->back()->withErrors(['msg' => 'Uživatel s uživatelským jménem ' . $username . ' neexistuje.']);
                }
                UserGroup::whereId($request->post("id"))->update(["users" => $request->post("users")]);
                return redirect()->route("usergroup.index");
            } else {
                return redirect()->back()->withErrors(['msg' => 'Nemáte dostatečná oprávnění k úpravě skupin.']);
            }
        }else{
            return abort(403);
        }
    }

    public function store(StoreUserGroupRequest $request)
    {
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            $permission = Auth::user()->permission;
            if ($permission == "admin") {
                $userListNotExist = [];
                foreach (explode(",", str_replace(" ", "", $request->post("users"))) as $username) {
                    $user = User::where("username", $username)->first();
                    if (!$user) array_push($userListNotExist, $username);
                }
                if (!empty($userListNotExist)) return redirect()->back()->withErrors(['msg' => 'Uživatelé s uživatelskými jmény ' . implode(",", $userListNotExist) . ' neexistuje.'])->withInput($request->all());
                UserGroup::create(["name" => $request->post("name"), "users" => $request->post("users")]);
                return redirect()->route("usergroup.index");
            } else {
                return redirect()->back()->withErrors(['msg' => 'Nemáte dostatečná oprávnění k vytváření skupin.'])->withInput($request->all());
            }
        }else{
            return abort(403);
        }
    }

    public function import(){
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            return view("usergroups.import");
        }else{
            return abort(403);
        }
    }

    public function importStart(String $id){
        $permission = Auth::user()->permission;
        if ($permission == "admin") {

            $client = new Client();
            $response = $client->get('https://graph.microsoft.com/v1.0/groups/' . $id , [
                'headers' => [
                    'Authorization' => 'Bearer ' . Cache::get("azureBearer")
                ],
            ]);
            $name = json_decode($response->getBody())->displayName;

            $client = new Client();
            $response = $client->get('https://graph.microsoft.com/v1.0/groups/' . $id . '/members', [
                'headers' => [
                    'Authorization' => 'Bearer ' . Cache::get("azureBearer")
                ],
            ]);

            $json = json_decode($response->getBody(), true);
            $users = implode(',', array_map(function ($entry) {
                $entry = explode("@", $entry["mail"])[0];
                return $entry;
            }, $json["value"]));

            return view("usergroups.importStart", compact("id", "name", "json", "users"));
        }else{
            return abort(403);
        }
    }

    public function microsoftImportSearch(Request $request){
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            $client = new Client();
            if ($request->get("search")) {
                $uri = 'https://graph.microsoft.com/v1.0/groups?$search="displayName:' . $request->get("search") . '"';
            } else {
                $uri = 'https://graph.microsoft.com/v1.0/groups';
            }
            $response = $client->get($uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . Cache::get("azureBearer"),
                    'ConsistencyLevel' => 'eventual'
                ],
            ]);

            return json_decode($response->getBody());
        }else{
            return abort(403);
        }
    }

    public function microsoftImport(String $id){
        $permission = Auth::user()->permission;
        if ($permission == "admin") {
            $client = new Client();
            $response = $client->get('https://graph.microsoft.com/v1.0/groups/' . $id . '/members', [
                'headers' => [
                    'Authorization' => 'Bearer ' . Cache::get("azureBearer")
                ],
            ]);

            return json_decode($response->getBody());
        }else{
            return abort(403);
        }
    }

}
