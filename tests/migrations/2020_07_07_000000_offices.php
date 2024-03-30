<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Offices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('city');
            $table->string('phone');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->string('territory');
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
        //
        Schema::dropIfExists('offices');
    }
}
