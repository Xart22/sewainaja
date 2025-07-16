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
            $table->string('email')->nullable()->unique();
            $table->string('phone_number')->unique();
            $table->string('address');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('pic_process')->nullable();
            $table->string('pic_process_phone_number')->nullable();
            $table->string('pic_installation')->nullable();
            $table->string('pic_installation_phone_number')->nullable();
            $table->string('pic_financial')->nullable();
            $table->string('pic_financial_phone_number')->nullable();
            $table->dateTime('contract_start');
            $table->dateTime('expired_at');
            $table->boolean('is_active')->default(true);
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
