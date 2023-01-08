<?php

use App\Ldap\User;
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

Route::get('/', [\App\Http\Controllers\IndexController::class, "index"])->name("index");;

Route::get('/users', function () {
    $users = User::all();
    return $users;
})->middleware('auth');

Route::get('/groups', function () {
    $users = \LdapRecord\Models\ActiveDirectory\ExchangeServer::all();
    return $users;
})->middleware('auth');

Route::get('/giverole', [\App\Http\Controllers\IndexController::class, "giveStudentRole"]);

Route::controller(\App\Http\Controllers\Payment\PaymentController::class)->group(function () {
    Route::get("/payment", ["uses" => "index", "type" => null])->name("payment.my");
    Route::get("/payment/paid", ["uses" => "index", "type" => "myPaid"])->name("payment.mypaid");
    Route::get("/payment/created", ["uses" => "index", "type" => "created"])->name("payment.created");
    Route::get("/payment/create","create")->name("payment.create");
    Route::get("/payment/{id}", "show")->name("payment.detail");
    Route::post("/payment","store")->name("payment.store");
    Route::get("/payment/edit/{id}","edit")->name("payment.edit");
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
