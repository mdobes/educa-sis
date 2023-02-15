<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\BankPaymentsLog;
use App\Models\Payment\Group;
use Illuminate\Http\Request;

class BankPaymentsLogController extends Controller
{
    /**
     * Vynucení příhlášení
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $offset = ($request->get("offset") ?? 0);
        $limit = ($request->get("limit") ?? 10);
        $rows = BankPaymentsLog::
            where("payer_account_number", "like", "%" . $request->get("search") . "%")
            ->orWhere("payer_account_name", "like", "%" . $request->get("search") . "%")
            ->orderBy("created_at", "desc")
            ->skip($offset)
            ->take($limit)
            ->get();
        $totalNotFiltered = count($rows);
        $total =  BankPaymentsLog::count();

        return compact("total", "totalNotFiltered", "rows");
    }

    public function index(){
        return view("payments.banklog");
    }
}
