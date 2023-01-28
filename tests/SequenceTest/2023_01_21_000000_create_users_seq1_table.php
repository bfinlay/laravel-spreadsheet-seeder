<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSeq1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users_seq1', function (Blueprint $table) {
            $table->bigIncrements('users_seq1_id');
            // name,email,email_verified_at,password
            $table->string('name');
            $table->string('email');
            $table->dateTime('email_verified_at');
            $table->string('password');
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
        Schema::dropIfExists('users_seq1');
    }
}
