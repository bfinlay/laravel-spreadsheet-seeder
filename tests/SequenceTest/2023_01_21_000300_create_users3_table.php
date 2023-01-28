<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users3', function (Blueprint $table) {
            $table->bigIncrements('id');
            // name,email,email_verified_at,password
            $table->string('name');
            $table->string('email');
            $table->dateTime('email_verified_at');
            $table->string('password');
            $table->timestamps();
        });

        Schema::table('users3', function(Blueprint $table) {
            $table->dropPrimary('users3_id_primary');
        });

        Schema::table('users3', function (Blueprint $table) {
            $table->bigIncrements('order')->after('id');
        });

        Schema::table('users3', function (Blueprint $table) {
            $table->dropPrimary('users3_order_primary');
            $table->primary('id');
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
        Schema::dropIfExists('users3');
    }
}
