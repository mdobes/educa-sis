<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Requests\Payment\SearchPaymentRequest;
use App\Http\Requests\Payment\ShowGroupRequest;
use App\Jobs\SendPaymentCreatedJob;
use App\Models\Payment\Group;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserGroup;
use Defr\QRPlatba\QRPlatba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return array
     */
    public function search(SearchPaymentRequest $request){
        $user = Auth::user();
        $offset = ($request->get("offset") ?? 0);
        $limit = ($request->get("limit") ?? 10);
        if($request->get("showType") == "my"){
            $rows = Group::where("name", "like", "%" . $request->get("search") . "%")->where("author", Auth::user()->username)->orderBy("created_at", "desc")->skip($offset)->take($limit)->get();
            $totalNotFiltered = count($rows);
            $total =  Group::where("author", Auth::user()->username)->count();

            return compact("total", "totalNotFiltered", "rows");
        }else if($request->get("showType") == "all" && $user->can("payments.view.all")){

            $rows = Group::where("name", "like", "%" . $request->get("search") . "%")->orderBy("created_at", "desc")->skip($offset)->take($limit)->get();
            $totalNotFiltered = count($rows);
            $total =  Group::count();

            return compact("total", "totalNotFiltered", "rows");
        }

        return abort(400);
    }

    public function showGroup(Group $group){
        $user = Auth::user();
        if ($user->username !== $group->author && !$user->can("payments.view.all")) return abort(403);
        return view("payments.group", compact("group"));
    }

    /**
     * Ukazuje seznamy plateb
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->route()->getAction()['type'];
        $user = Auth::user();
        if ($type == "my"){
            $title = "Mnou vytvořené platby";
        }else if($type == "all"){
            $title = "Všechny vytvořené platby";
        }
        $username = $user->username;

        return view('payments.index', compact("title", "username", "type"));
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

            $group = Group::create([
                "name" => $request->post("title"),
                "author" => $username
            ]);

            $authorName = Auth::user()->name;

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
                    $data["group"] = $group["id"];

                    $payment = Payment::create($data);

                    $details['authorName'] = Auth::user()->name;
                    $details['paymentId'] = $payment;
                    $details["username"] = $info[1];
                    dispatch(new SendPaymentCreatedJob($details));

                    $route = "payment.created";
                }else if($info[0] == "group"){
                    $userGroup = UserGroup::where("id", "=", $info[1])->firstOrFail();
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

                        $details['authorName'] = Auth::user()->name;
                        $details['paymentId'] = $payment;
                        $details["username"] = $u;
                        dispatch(new SendPaymentCreatedJob($details));
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
            $qrPlatba = new QRPlatba();

            $qrPlatba->setAccount(config("bank.bank.acc_number"))
            ->setVariableSymbol($data->payerUserId)
                ->setSpecificSymbol($data->specific_symbol)
                ->setAmount($data->remain)
                ->setCurrency('CZK');

            $img = $qrPlatba->getDataUri();

            return view('payments.detail', compact("data", "user", "username", "img"));
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
