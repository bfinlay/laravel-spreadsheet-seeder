<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFakeNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('fake_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            // name,email,email_verified_at,password
            $table->string('Gender');
            $table->string('Title');
            $table->string('GivenName');
            $table->string('MiddleInitial');
            $table->string('Surname');
            $table->string('StreetAddress');
            $table->string('City');
            $table->string('State');
            $table->string('ZipCode');
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
        Schema::dropIfExists('fake_names');
    }
}
