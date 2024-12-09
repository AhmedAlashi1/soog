<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('country_code' , 20);
            $table->decimal('discount' , 7 , 3);
            $table->integer('count_number');
            $table->timestamp('end_at')->nullable()->default(null);
            $table->tinyInteger('type');
            $table->integer('percent');
            $table->integer('use_number');
            $table->integer('code_limit');
            $table->integer('code_max');
            $table->enum('status', array('0', '1'))->default('1');
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
        Schema::dropIfExists('coupons');
    }
}
