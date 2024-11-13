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
        Schema::create('sw_information', function (Blueprint $table) {
            $table->id();
            $table->string('sw_name')->nullable();
            $table->string('sw_relocation')->nullable();
            $table->string('sw_address')->nullable();
            $table->string('sw_installation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_information');
    }
};
