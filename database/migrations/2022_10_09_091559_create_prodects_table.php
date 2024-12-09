<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('clothes')){
            Schema::create('clothes', function (Blueprint $table) {
                $table->id();
                $table->string('title_ar');
                $table->string('title_en');
                $table->string('nota_ar');
                $table->string('nota_en');
                $table->string('image');
                $table->float('price');
                $table->integer('quntaty');
                $table->foreignId('cat_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate(); 
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();            
                $table->enum('status', array('0', '1'))->default('1')->comment("Active = 1 , Not Active = 0");
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodects');
    }
}
