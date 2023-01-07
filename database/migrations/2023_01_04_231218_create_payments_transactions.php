<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("payment_id", false, true);
            $table->integer("amount");
            $table->string("author");
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments_transactions');
    }
};
