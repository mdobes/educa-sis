<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment\Group;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PaymentController extends Controller
{

    /**
     * Vynucení příhlášení
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ukazuje seznamy plateb
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request, ? String $id = null)
    {
        $type = $request->route()->getAction()['type'];
        $showGrouping = false;
        $showPaid = false;
        $showPayer = false;
        $showBacklink = false;
        $user = Auth::user();
        $username = $user->username;

        $selectCols = [
            'payments_list.id AS id',
            'payments_list.title as title',
            'payments_list.amount as amount',
            'payments_list.author as author',
            'payments_list.payer as payer',
            'payments_list.due as due',
            'payments_list.created_at as created_at',
            DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), payments_list.amount) as `remain`'),
            DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), 0) as paid')
        ];

        $groupByCols = [
            'payments_list.id', 'payments_list.title', 'payments_list.amount', 'payments_list.due', 'payments_list.author', 'payments_list.payer', 'payments_list.created_at'
        ];

        if (!isset($type)) {
            $title = "Mé platby";
            $showPaid = true;

            $data = DB::table('payments_list')
                ->leftJoin('payments_transactions', 'payments_list.id', '=', 'payments_transactions.payment_id')
                ->select($selectCols)
                ->groupBy($groupByCols)
                ->orderBy("due", "asc")
                ->having("remain", "!=", 0)
                ->where("payer", "=", Auth::user()->username)
                ->paginate(15);
        }else if ($type == "created"){
            $title = "Mnou vytvořené platby";
            $showGrouping = true;
            $showPayer = true;

            $data = DB::table('payments_list')
                ->leftJoin('payments_transactions', 'payments_list.id', '=', 'payments_transactions.payment_id')
                ->select($selectCols)
                ->groupBy($groupByCols)
                ->orderBy("payments_list.created_at", "asc")
                ->where("payments_list.author", "=", Auth::user()->username)
                ->paginate(15);
        }else if ($type == "myPaid"){
            $title = "Mé uhrazené platby";
            $showPaid = true;
            $showPayer = false;

            $data = DB::table('payments_list')
                ->leftJoin('payments_transactions', 'payments_list.id', '=', 'payments_transactions.payment_id')
                ->select($selectCols)
                ->groupBy($groupByCols)
                ->orderBy("due", "asc")
                ->having("remain", "=", 0)
                ->where("payments_list.payer", "=", Auth::user()->username)
                ->paginate(15);
        }else if ($type == "group"){
            $group = Group::where("id", "=", $id)->firstOrFail();
            $title = "Detail skupiny plateb ID $group->id";
            $showPayer = true;
            $showGrouping = true;
            $showBacklink = true;

            $data = DB::table('payments_list')
                ->leftJoin('payments_transactions', 'payments_list.id', '=', 'payments_transactions.payment_id')
                ->select($selectCols)
                ->groupBy($groupByCols)
                ->orderBy("due", "asc")
                ->where("payments_list.group", "=", $group->id)
                ->paginate(15);
        }
        return view('payments.index', compact("data", "title", "showGrouping", "showPaid", "user", "username", "showPayer", "showBacklink"));
    }

    /**
     * Ukáže formulář pro vytvoření nové platby
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        if($user->hasPermissionTo('payments.create')) {
            $formButtonTitle = "Vytvořit";
            return view('payments.create', compact("formButtonTitle"));
        }else{
            return abort(403);
        }
    }

    /**
     * Vytváří platby (pro uživatele i skupiny uživatelů)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PaymentRequest $request)
    {
        $user = Auth::user();
        $username = $user->username;

        $route = "payment.created";
        $routeRedirect = null;

        if($user->hasPermissionTo('payments.create')) {

            foreach($request->post("payer") as $list){
                $info = explode(":", $list);
                if ($info[0] == "user"){
                    $payment = Payment::where("payer", "=", $info[1])->latest()->firstOr(function () {
                        return ["specific_symbol" => 0];
                    });

                    $data = $request->only("title", "amount", "due");
                    $data["payer"] = $info[1];
                    $data["specific_symbol"] = $payment["specific_symbol"] + 1;
                    $data["type"] = "normal";
                    $data["author"] = $username;

                    $payment = Payment::create($data);
                    $route = "payment.created";
                }else if($info[0] == "group"){
                    $userGroup = UserGroup::where("id", "=", $info[1])->firstOrFail();
                    $group = Group::create([
                            "name" => $request->post("title"),
                            "author" => $username
                    ]);
                    foreach(explode(",", $userGroup->users) as $u) {
                        $payment = Payment::where("payer", "=", $u)->latest()->firstOr(function () {
                            return ["specific_symbol" => 0];
                        });

                        $data = $request->only("title", "amount", "due");
                        $data["payer"] = $u;
                        $data["specific_symbol"] = $payment["specific_symbol"] + 1;
                        $data["type"] = "normal";
                        $data["author"] = $username;
                        $data["group"] = $group["id"];

                        $payment = Payment::create($data);
                    }
                }

            }

            return redirect()->route($route, $routeRedirect);



        }else{
            return abort(403);
        }
    }

    /**
     * Ukáže detaily platby.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Payment::findOrFail($id);
        $user = Auth::user();
        $username = $user->username;

        if($data->author == $username || $data->payer == $username || $user->hasPermissionTo('payments.any.view')){
            return view('payments.detail', compact("data", "user", "username"));
        }else{
            return abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\View
     */
    public function edit($id)
    {
        $data = Payment::findOrFail($id);
        $formButtonTitle = "Editovat";
        //return $data;
        return view('payments.edit', compact("data", "formButtonTitle"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchPayers(Request $request){

        $request->validate(["search" => "string|min:3|max:50|required"]);

        $users = User::select("username AS typeId", "name as text")->where("name", "like", "%" . $request->get("search") . "%")->get();
        $groups = UserGroup::select("id as typeId", "name as text")->where("name", "like", "%" . $request->get("search") . "%")->get();

        return compact("users", "groups");
    }
}
