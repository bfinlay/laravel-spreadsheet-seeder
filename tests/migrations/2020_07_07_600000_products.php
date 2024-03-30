<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedBigInteger('product_line_id');
            $table->foreign('product_line_id')->references('id')->on('product_lines');
            $table->string('scale');
            $table->string('vendor');
            $table->text('description');
            $table->unsignedInteger('qty');
            $table->decimal('buy_price',10,2);
            $table->decimal('msrp',10,2);
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
        Schema::dropIfExists('products');
    }
}
