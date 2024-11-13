<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('group_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('zip_code');
            $table->string('country');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('pic_process');
            $table->string('pic_process_phone_number');
            $table->string('pic_installation');
            $table->string('pic_installation_phone_number');
            $table->string('pic_financial');
            $table->string('pic_financial_phone_number');
            $table->bigInteger('hw_id')->unsigned();
            $table->foreign('hw_id')->references('id')->on('hw_informations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
