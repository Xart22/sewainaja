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
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hw_id')->unsigned();
            $table->string('nama_pelapor');
            $table->string('no_wa_pelapor');
            $table->string('keperluan');
            $table->longText('keluhan');
            $table->bigInteger('responded_by')->unsigned()->nullable();
            $table->bigInteger('tekhnisi_id')->unsigned()->nullable();
            $table->string('status')->default('Waiting');
            $table->foreign('hw_id')->references('id')->on('hardware_information')->onDelete('cascade');
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tekhnisi_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhans');
    }
};
