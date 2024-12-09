<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->unique();
            $table->string('mobile_number', 20)->nullable()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password', 255);
            $table->enum('status', array('active', 'pending_activation', 'inactive'))->default('pending_activation');
            $table->string('address');
            $table->string('avatar');
            $table->string('activation_code')->nullable();
            $table->string('ip_address');
            $table->integer('resend_code_count');
            $table->string('device_token');
            $table->rememberToken();
            $table->decimal('credit' , 10 , 3);
            $table->float('points');
            // $table->string('city_id');
            // $table->integer('city_id')->unsigned();
            // $table->foreign('city_id')->references('id')->on('cities')
            //     ->onDelete('cascade') ->onUpdate('cascade');
            $table->foreignId('city_id')->constrained('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('governorates')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('email_wrong_active')->default(0);
            $table->enum('email_status', ['active', 'pending_activation', 'inactive'])->default('pending_activation');
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
        Schema::dropIfExists('app_users');
    }
}
