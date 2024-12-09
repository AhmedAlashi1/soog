<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_gates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->integer('sort_order');
            $table->string('username');
            $table->string('password');
            $table->string('sender');
            $table->enum('is_active', array('0', '1'))->default('1')->comment("Active = 1 , Not Active = 0");
            $table->string('gateway');
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
        Schema::dropIfExists('sms_gates');
    }
}
