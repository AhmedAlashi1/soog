<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_log', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('days');
            $table->timestamp('send_at')->nullable()->default(null);
            $table->mediumText('message');
            $table->string('url');
            $table->foreignId('cat_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('clothes')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', array('0', '1'))->default('1');
            $table->tinyInteger('type')->default('0');
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
        Schema::dropIfExists('notification_logs');
    }
}
