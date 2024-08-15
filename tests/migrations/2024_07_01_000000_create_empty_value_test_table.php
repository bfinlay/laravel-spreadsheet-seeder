<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmptyValueTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('empty_value_test', function (Blueprint $table) {
            $table->bigIncrements('id');
            // name,email,email_verified_at,password
            $table->char('null_column', 15)->nullable()->default("default value");
            $table->boolean('true_column')->nullable()->default(false)->index();
            $table->boolean('false_column')->nullable()->default(true)->index();
            $table->boolean('skip_row')->nullable()->default(false);
            $table->char('blank_column', 15)->nullable()->default("default value");
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
        Schema::dropIfExists('empty_value_test');
    }
}
