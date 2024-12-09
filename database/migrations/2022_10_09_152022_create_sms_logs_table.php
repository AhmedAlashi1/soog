<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_log', function (Blueprint $table) {
            $table->id();
            $table->text('numbers');
            $table->text('message');
            $table->enum('status', array('0', '1'))->default('1')->comment("Active = 1 , Not Active = 0");
            $table->mediumText('response');
            $table->string('gate_message');
            $table->foreignId('gate_id')->constrained('sms_gates')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sms_logs');
    }
}
