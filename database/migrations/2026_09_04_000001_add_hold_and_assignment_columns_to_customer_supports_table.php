<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_supports', function (Blueprint $table) {
            $table->datetime('teknisi_assigned_at')->nullable()->after('teknisi_id');
            $table->longText('hold_reason')->nullable()->after('waktu_selesai');
            $table->datetime('hold_started_at')->nullable()->after('hold_reason');
            $table->unsignedInteger('total_hold_menit')->default(0)->after('hold_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('customer_supports', function (Blueprint $table) {
            $table->dropColumn(['teknisi_assigned_at', 'hold_reason', 'hold_started_at', 'total_hold_menit']);
        });
    }
};
