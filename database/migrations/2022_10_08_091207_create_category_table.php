<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('categories')){
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('title_en');
                $table->string('title_ar');
                $table->string('description_en');
                $table->string('description_ar');
                $table->string('image')->nullable();
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
        Schema::dropIfExists('category');
    }
}
