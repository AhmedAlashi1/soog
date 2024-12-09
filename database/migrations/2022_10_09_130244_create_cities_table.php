<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governorat_id')->constrained('governorates')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title_ar');
            $table->string('title_en');
            $table->integer('delivery_cost');
            $table->tinyInteger('far_zone');
            $table->integer('order_limit');
            $table->enum('status', array('0', '1'))->default('1')->comment("Active = 1 , Not Active = 0");
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}