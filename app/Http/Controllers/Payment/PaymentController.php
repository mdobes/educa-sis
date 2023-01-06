<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('payments_list')
            ->leftJoin('payments_transactions', 'payments_list.variable_symbol', '=', 'payments_transactions.variable_symbol')
            ->select(
                'payments_list.variable_symbol AS variable_symbol',
                'payments_list.title as title',
                'payments_list.amount as amount',
                'payments_list.due as due',
                DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), payments_list.amount) as `remain`'),
                DB::raw('COALESCE(CAST(payments_list.amount - SUM(payments_transactions.amount) As int), 0) as paid')
            )
            ->groupBy('payments_list.variable_symbol', 'payments_list.title', 'payments_list.amount', 'payments_list.due')
            ->orderBy("due", "asc")
            ->having("remain", "!=", 0)
            ->paginate(15);

        return view('payments.index', compact("data"));
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

        $payment = Payment::create($request->all());

        return redirect()->route("payment.detail", $payment->variable_symbol);
        //return $request->all();
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
