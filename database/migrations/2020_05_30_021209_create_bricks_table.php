<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBricksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brick', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->nullable();
            $table->string('orderId')->nullable();
            $table->string('side')->nullable();
            $table->string('type')->nullable();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
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
        Schema::dropIfExists('bricks');
    }
}
