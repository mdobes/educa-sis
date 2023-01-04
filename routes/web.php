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

Route::get("/payment", [\App\Http\Controllers\PaymentController::class, "index"]);
Route::get("/payment/create", [\App\Http\Controllers\PaymentController::class, "create"]);
Route::post("/payment", [\App\Http\Controllers\PaymentController::class, "store"]);
Route::get("/payment/{id}", [\App\Http\Controllers\PaymentController::class, "show"])->name("payment.detail");

