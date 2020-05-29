<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ticker');
        Schema::create('ticker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eventType')->nullable();
            $table->string('eventTime')->nullable();
            $table->string('symbol')->nullable();
            $table->double('priceChange',16,10)->nullable();
            $table->float('percentChange',5,4)->nullable();
            $table->double('averagePrice',16,10)->nullable();
            $table->double('prevClose',16,10)->nullable();
            $table->double('close',16,10)->nullable();
            $table->float('closeQty',10)->nullable();
            $table->float('bestBid',10)->nullable();
            $table->float('bestBidQty',10)->nullable();
            $table->float('bestAsk',10)->nullable();
            $table->float('bestAskQty',10)->nullable();
            $table->double('open',16,10)->nullable();
            $table->double('high',16,10)->nullable();
            $table->double('low',16,10)->nullable();
            $table->double('volume',16)->nullable();
            $table->double('quoteVolume',16)->nullable();
            $table->bigInteger('openTime')->nullable();
            $table->bigInteger('closeTime')->nullable();
            $table->integer('firstTradeId')->nullable();
            $table->integer('lastTradeId')->nullable();
            $table->integer('numTrades')->nullable();

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
        Schema::dropIfExists('ticker');
    }
}
