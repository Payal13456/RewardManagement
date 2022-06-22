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
        Schema::create('country_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('phone_code')->nullable();
            $table->string('country_code',45)->nullable();
            $table->string('country_name',100)->nullable();
            $table->string('symbol',100)->nullable();
            $table->string('capital',100)->nullable();
            $table->string('currency',100)->nullable();
            $table->string('continent',100)->nullable();
            $table->string('continent_code',100)->nullable();
            $table->string('alpha_3',100)->nullable();
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
        Schema::dropIfExists('country_codes');
    }
};
