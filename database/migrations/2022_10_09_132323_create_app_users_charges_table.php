<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('address');
            $table->double('lat');
            $table->double('lng');
            $table->enum('status', array('0', '1'))->default('1');
            $table->timestamps();
            $table->string('flat');
            $table->string('floor');
            $table->string('governate');
            $table->string('city');
            $table->string('block');
            $table->string('street');
            $table->string('title');
            $table->string('building');
            $table->string('avenue');
            $table->foreignId('city_id')->constrained('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('governorates')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('type');
            $table->mediumText('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_users_charges');
    }
}
