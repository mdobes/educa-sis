<?php

use App\Ldap\User;
use http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\IndexController::class, "index"])->name("index")->middleware('auth');

Route::get("/payment/banklog", [\App\Http\Controllers\Payment\BankPaymentsLogController::class, "index"])->name("payment.banklog");
Route::get("/payment/banklog/search", [\App\Http\Controllers\Payment\BankPaymentsLogController::class, "search"])->name("payment.banklog.search");

Route::controller(\App\Http\Controllers\Payment\PaymentController::class)->prefix("payment")->group(function () {
    Route::get("/", ["uses" => "index", "type" => "my"])->name("payment.show");
    Route::get("/my", ["uses" => "index", "type" => "my"])->name("payment.show.my");
    Route::get("/all", ["uses" => "index", "type" => "all"])->name("payment.show.all");
    Route::post("/","store")->name("payment.store");
    Route::get("/search","search")->name("payment.search");
    Route::get("/group/{group}", "showGroup")->name("payment.group");
    Route::get("/searchpayers", "searchPayers")->name("payment.searchpayers");
    Route::get("/paid", ["uses" => "index", "type" => "myPaid"])->name("payment.mypaid");
    Route::get("/created", ["uses" => "index", "type" => "created"])->name("payment.created");
    Route::get("/create","create")->name("payment.create");
    Route::get("/{id}", "show")->name("payment.detail");
})->middleware('auth');

Route::controller(\App\Http\Controllers\User\UserController::class)->prefix("user")->group(function () {
    Route::get("/","index")->name("users.index");
    Route::get("/search", "search")->name("users.search");
    Route::get("/{id}", "edit")->name("users.edit");
    Route::patch("/", "update")->name("users.update");
})->middleware('auth');

Route::controller(\App\Http\Controllers\User\UserGroupController::class)->prefix("usergroup")->group(function () {
    Route::get("/", "index")->name("usergroup.index");
    Route::get("/search", "search")->name("usergroup.search");
    Route::get("/import", "import")->name("usergroup.import");
    Route::get("/import/{id}", "importStart")->name("usergroup.import.start");
    Route::get("/create", "create")->name("usergroup.create");
    Route::get("/{id}", "edit")->name("usergroup.edit");
    Route::post("/", "store")->name("usergroup.store");
    Route::patch("/", "update")->name("usergroup.update");

    Route::prefix("/microsoft")->group(function () {
        Route::get("/search", "microsoftImportSearch")->name("usergroup.microsoft.search");
    });

})->middleware('auth');

Route::controller(\App\Http\Controllers\Payment\TransactionsController::class)->group(function () {
    Route::post("/transaction", "store")->name("transaction.store");
    Route::get("/transaction/{id}/unpair", "unPair")->name("transaction.unpair");
    Route::get("/transaction/{id}/restore", "restorePair")->withTrashed()->name("transaction.restorePair");
})->middleware('auth');


Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function () {
    Route::get("/login", "get")->name("login.index");
    Route::post("/login", "post")->name("login.post");
})->middleware('auth');;

Route::get('/logout', function () {
    Auth::logout();
    return redirect("/login");
})->middleware('auth');
