<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('mobile_number' , 20);
            $table->string('full_name');
            $table->foreignId('country_code')->constrained('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('cat_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('password');
            $table->enum('status', array('active','pending_activation','inactive'))->default('pending_activation');
            $table->string('car');
            $table->string('avatar');
            $table->string('password_code');
            $table->string('fbm_id');
            $table->string('registration_id');
            $table->string('login');
            $table->tinyInteger('avilable');
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
        Schema::dropIfExists('drivers');
    }
}
