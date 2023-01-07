<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->route()->getAction()['type'];
        $showGrouping = false;
        if (!isset($type)) {

            $title = "Mé platby";

            $data = DB::table('payments_list')
                ->leftJoin('payments_transactions', 'payments_list.id', '=', 'payments_transactions.payment_id')
                ->select(
                    'payments_list.id AS id',
                    'payments_list.title as title',
                    'payments_list.amount as amount',
                    'payments_list.due as due',
                    DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), payments_list.amount) as `remain`'),
                    DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), 0) as paid')
                )
                ->groupBy('payments_list.id', 'payments_list.title', 'payments_list.amount', 'payments_list.due')
                ->orderBy("due", "asc")
                ->having("remain", "!=", 0)
                ->where("payer", "=", Auth::user()->username)
                ->paginate(15);
        }else if ($type == "created"){
            $title = "Mnou vytvořené platby";
            $showGrouping = true;

            $data = DB::table('payments_list')
                ->leftJoin('payments_transactions', 'payments_list.id', '=', 'payments_transactions.payment_id')
                ->select(
                    'payments_list.id AS id',
                    'payments_list.title as title',
                    'payments_list.amount as amount',
                    'payments_list.due as due',
                    DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), payments_list.amount) as `remain`'),
                    DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), 0) as paid')
                )
                ->groupBy('payments_list.id', 'payments_list.title', 'payments_list.amount', 'payments_list.due')
                ->orderBy("due", "asc")
                ->where("payments_list.author", "=", Auth::user()->username)
                ->paginate(15);
        }
        return view('payments.index', compact("data", "title", "showGrouping"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $formButtonTitle = "Vytvořit";
        return view('payments.create', compact("formButtonTitle"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        $payment = Payment::where("payer", "=", $request->get("payer"))->latest()->firstOr(function () {
            return ["id" => 0];
        });

        $data = $request->all();
        $data["specific_symbol"] = $payment["id"] + 1;
        $data["type"] = "normal";
        $data["author"] = Auth::user()->username;

        $payment = Payment::create($data);

        return redirect()->route("payment.detail", $payment->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Payment::findOrFail($id);
        return view('payments.detail', compact("data"));
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
}
