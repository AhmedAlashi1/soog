<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->integer('layout')->nullable();
            $table->string('lauout_title')->nullable();
            $table->string('image')->nullable();
            $table->integer('days')->nullable();
            $table->integer('cost')->nullable();
            $table->enum('status', array('0', '1'))->default('1');
            $table->foreignId('cat_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate(); 
            $table->foreignId('product_id')->constrained('clothes')->cascadeOnDelete()->cascadeOnUpdate();    
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
        Schema::dropIfExists('ads');
    }
}
