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
        Schema::create('customer_supports', function (Blueprint $table) {
            $table->id();
            $table->string('no_ticket')->unique();
            $table->bigInteger('customer_id')->unsigned();
            $table->string('nama_pelapor');
            $table->string('no_wa_pelapor');
            $table->string('keperluan');
            $table->longText('message');
            $table->bigInteger('responded_by')->unsigned()->nullable();
            $table->bigInteger('teknisi_id')->unsigned()->nullable();
            $table->string('status_process')->default('Waiting');
            $table->string('status_cso')->default('Waiting');
            $table->string('status_teknisi')->nullable();
            $table->time('waktu_respon')->nullable();
            $table->time('waktu_perjalanan')->nullable();
            $table->time('waktu_pengerjaan')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('customer_supports');
    }
};
