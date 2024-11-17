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
        Schema::create('log_keluhans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keluhan_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('keterangan');
            $table->foreign('keluhan_id')->references('id')->on('keluhans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_keluhans');
    }
};
