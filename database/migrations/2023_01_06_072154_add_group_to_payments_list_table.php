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
        Schema::table('payments_list', function (Blueprint $table) {
            $table->bigInteger("group")->unsigned()->nullable();
            $table->foreign('group')->references('id')->on('payments_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments_list', function (Blueprint $table) {
            //
        });
    }
};
