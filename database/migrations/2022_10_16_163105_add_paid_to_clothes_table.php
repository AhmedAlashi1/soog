<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToClothesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clothes', function (Blueprint $table) {
            $table->string('price_after' , 50);
            $table->string('end_date' , 50);
            $table->string('lat' , 50);
            $table->string('lng' , 50);
            $table->string('code' , 20);
            $table->tinyInteger('type');
            $table->tinyInteger('confirm');
            $table->integer('sort_order');
            $table->integer('order_limit');
            $table->string('barcode' , 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clothes', function (Blueprint $table) {
            //
        });
    }
}
