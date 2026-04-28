<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $customers = DB::table('customers')
            ->select('id', 'contract_start', 'expired_at')
            ->whereNotNull('contract_start')
            ->whereNotNull('expired_at')
            ->get();

        foreach ($customers as $customer) {
            $existingContract = DB::table('customer_contracts')
                ->where('customer_id', $customer->id)
                ->where('contract_start', $customer->contract_start)
                ->where('contract_end', $customer->expired_at)
                ->first();

            if ($existingContract) {
                $contractId = $existingContract->id;
            } else {
                $contractId = DB::table('customer_contracts')->insertGetId([
                    'customer_id' => $customer->id,
                    'contract_start' => $customer->contract_start,
                    'contract_end' => $customer->expired_at,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('hardware')
                ->where('customer_id', $customer->id)
                ->whereNull('customer_contract_id')
                ->update(['customer_contract_id' => $contractId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hardware')->update(['customer_contract_id' => null]);
        DB::table('customer_contracts')->delete();
    }
};
