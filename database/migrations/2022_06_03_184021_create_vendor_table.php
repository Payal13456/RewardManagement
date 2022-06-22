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
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->string('mobile_no',15)->nullable();
            $table->string('email',50)->nullable();
            $table->string('shop_name',100)->nullable();
            $table->string('website',100)->nullable();
            $table->string('shop_email',100)->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('category_id')->unsigned();
            $table->string('location',100)->nullable();
            $table->string('lat',100)->nullable();
            $table->string('long',100)->nullable();
            $table->integer('status')->nullable()->comment('0-inactive, 1-active');
            $table->integer('is_blocked')->nullable()->comment('0-block, 1-unblock');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor');
    }
};
