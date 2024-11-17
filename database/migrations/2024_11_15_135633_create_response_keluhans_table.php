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
        Schema::create('response_keluhans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keluhan_id')->unsigned();
            $table->time('waktu_respon');
            $table->time('waktu_perjalanan')->nullable();
            $table->time('waktu_pengerjaan')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_keluhans');
    }
};
