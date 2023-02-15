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

Route::get('/users', function () {
    $users = User::all();
    return $users;
})->middleware('auth');

Route::get('/groups', function () {
    $users = \LdapRecord\Models\ActiveDirectory\ExchangeServer::all();
    return $users;
})->middleware('auth');

Route::get('/giverole', [\App\Http\Controllers\IndexController::class, "giveStudentRole"]);

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
    Route::get("/edit/{id}","edit")->name("payment.edit");
    Route::get("/{id}", "show")->name("payment.detail");
})->middleware('auth');


Route::controller(\App\Http\Controllers\Payment\TransactionsController::class)->group(function () {
    Route::post("/transaction", "store")->name("transaction.store");
})->middleware('auth');


Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function () {
    Route::get("/login", "get")->name("login.index");
    Route::post("/login", "post")->name("login.post");
})->middleware('auth');;

Route::get('/logout', function () {
    Auth::logout();
    return redirect("/login");
})->middleware('auth');
