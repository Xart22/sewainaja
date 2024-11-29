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
        Schema::create('ulasan_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_support_id')->unsigned();
            $table->bigInteger('cso_id')->unsigned();
            $table->integer('rating_cso');
            $table->text('ulasan_cso')->nullable();
            $table->bigInteger('teknisi_id')->unsigned();
            $table->integer('rating_teknisi');
            $table->text('ulasan_teknisi')->nullable();
            $table->text('ulasan_system')->nullable();
            $table->foreign('customer_support_id')->references('id')->on('customer_supports')->onDelete('cascade');
            $table->foreign('cso_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teknisi_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan_customers');
    }
};
