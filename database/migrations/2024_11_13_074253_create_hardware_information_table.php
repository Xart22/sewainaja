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
        Schema::create('hardware', function (Blueprint $table) {
            $table->id();
            $table->string('hw_name')->nullable();
            $table->string('hw_type');
            $table->string('hw_brand');
            $table->string('hw_model');
            $table->string('hw_serial_number')->unique();
            $table->string('hw_image')->nullable();
            $table->string('hw_relocation')->nullable();
            $table->string('hw_technology')->nullable();
            $table->string('hw_bw_color')->nullable();
            $table->longText('hw_description')->nullable();
            $table->boolean('used_status')->default(false);
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardware_information');
    }
};
