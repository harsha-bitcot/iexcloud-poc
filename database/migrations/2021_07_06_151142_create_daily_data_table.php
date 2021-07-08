<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_data', function (Blueprint $table) {
            $table->id();
            $table->string('company_symbol');
            $table->date('date');
            $table->decimal('close', $precision = 10, $scale = 3);
            $table->decimal('high', $precision = 10, $scale = 3);
            $table->decimal('low', $precision = 10, $scale = 3);
            $table->decimal('open', $precision = 10, $scale = 3);
            $table->bigInteger('volume');
            $table->decimal('changeOverTime', $precision = 17, $scale = 15);
            $table->decimal('marketChangeOverTime', $precision = 17, $scale = 15);
            $table->decimal('uOpen', $precision = 10, $scale = 3);
            $table->decimal('uClose', $precision = 10, $scale = 3);
            $table->decimal('uHigh', $precision = 10, $scale = 3);
            $table->decimal('uLow', $precision = 10, $scale = 3);
            $table->bigInteger('uVolume');
            $table->decimal('fOpen', $precision = 10, $scale = 3);
            $table->decimal('fClose', $precision = 10, $scale = 3);
            $table->decimal('fHigh', $precision = 10, $scale = 3);
            $table->decimal('fLow', $precision = 10, $scale = 3);
            $table->bigInteger('fVolume');
            $table->decimal('change', $precision = 10, $scale = 3);
            $table->decimal('changePercent', $precision = 8, $scale = 4);
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
        Schema::dropIfExists('daily_data');
    }
}
