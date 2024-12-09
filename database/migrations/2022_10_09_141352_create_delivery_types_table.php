<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->enum('status', array('0', '1'))->default('1');
            $table->timestamps();
            $table->string('slug');
            $table->time('time_from');
            $table->time('time_to');
            $table->tinyInteger('sat');
            $table->tinyInteger('sun');
            $table->tinyInteger('mon');
            $table->tinyInteger('tue');
            $table->tinyInteger('wed');
            $table->tinyInteger('thu');
            $table->tinyInteger('fri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_types');
    }
}
