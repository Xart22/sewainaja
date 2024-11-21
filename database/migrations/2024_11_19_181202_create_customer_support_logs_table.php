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
        Schema::create('customer_support_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_support_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('status');
            $table->text('message');
            $table->foreign('customer_support_id')->references('id')->on('customer_supports')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_support_logs');
    }
};
