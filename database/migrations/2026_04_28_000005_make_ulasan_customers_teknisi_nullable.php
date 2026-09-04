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
        Schema::table('ulasan_customers', function (Blueprint $table) {
            $table->bigInteger('teknisi_id')->unsigned()->nullable()->change();
            $table->integer('rating_teknisi')->nullable()->change();
            $table->text('ulasan_teknisi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan_customers', function (Blueprint $table) {
            $table->bigInteger('teknisi_id')->unsigned()->nullable(false)->change();
            $table->integer('rating_teknisi')->nullable(false)->change();
            $table->text('ulasan_teknisi')->nullable()->change();
        });
    }
};
