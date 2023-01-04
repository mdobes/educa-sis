<?php

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

Route::get('/', function () {
    $data = ["menu" => "home"];

    return view("main", compact("data"));
});

Route::controller(\App\Http\Controllers\PaymentController::class)->group(function () {
    Route::get("/payment", "index")->name("payment.index");
    Route::get("/payment/create","create")->name("payment.create");
    Route::post("/payment","store")->name("payment.store");
    Route::get("/payment/{id}", "show")->name("payment.detail");
});
