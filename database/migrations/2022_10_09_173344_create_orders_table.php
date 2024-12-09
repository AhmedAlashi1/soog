<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', array('new','pay_pending','shipping_complete','shipping','complete'))->default('new');
            $table->decimal('total_cost' , 10 , 3 );
            $table->foreignId('payment_id')->constrained('payment')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('delivery_id')->constrained('deliveries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->string('lat');
            $table->string('lng');
            $table->mediumText('notes');
            $table->mediumText('comment');
            $table->tinyInteger('payment_status');
            $table->string('user_agent');
            $table->string('promo_code');
            $table->tinyInteger('delivery_type');
            $table->timestamp('delivery_date')->nullable()->default(null);
            $table->foreignId('time_id')->constrained('times')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('use_credit');
            $table->tinyInteger('credit');
            $table->tinyInteger('return_credit')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
