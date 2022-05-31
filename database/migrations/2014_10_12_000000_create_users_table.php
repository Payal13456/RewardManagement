<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile_no',10)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',255)->nullable();
            $table->string('location',255)->nullable();
            $table->string('emirates_id',45)->unique()->nullable();
            $table->string('passport_no',45)->unique()->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->integer('role')->comment('1-admin, 2-user, 3-vendor')->nullable();
            $table->integer('status')->default(2)->comment('0-block, 1-unblock, 2-active');
            $table->integer('otp')->nullable();
            $table->dateTime('otp_valid_from')->nullable();
            $table->dateTime('otp_valid_upto')->nullable();
            $table->integer('otp_status')->default(0)->comment('0-not-verified, 1-verified');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
