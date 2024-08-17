<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDateTimeTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('date_time_test', function (Blueprint $table) {
            $table->bigIncrements('id');
            // name,email,email_verified_at,password
            $table->dateTime('excel_format')->nullable();
            $table->dateTime('unix_format')->nullable();
            $table->dateTime('string_format_1',3)->nullable();
            $table->dateTime('string_format_2')->nullable();
            $table->dateTime('default_value')->nullable()->default('2024-08-15 13:49:02');
            $table->text('test');

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
        Schema::dropIfExists('date_time_test');
    }
}
