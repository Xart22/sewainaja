<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Keperluan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keperluans = ["Service", "Order", "Spear Part", "Toner", "Lainnya"];

        foreach ($keperluans as $keperluan) {
            DB::table('keperluans')->insert([
                'name' => $keperluan,
                'created_at' => now(),
            ]);
        }
    }
}
