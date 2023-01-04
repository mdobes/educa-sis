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
        Schema::create('payments_list', function (Blueprint $table) {
            $table->id("variable_symbol");
            $table->string("payer");
            $table->string("author");
            $table->string("type", 10);
            $table->string("title");
            $table->integer("amount");
            $table->date("due");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments_list');
    }
};
