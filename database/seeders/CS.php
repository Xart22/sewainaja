<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class CS extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //faker data
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 2000; $i++) {
            $customer = [
                'no_ticket' => $faker->unique()->randomNumber(8),
                'customer_id' => 1,
                'nama_pelapor' => $faker->name,
                'no_wa_pelapor' => $faker->phoneNumber,
                'keperluan' => $faker->sentence,
                'message' => $faker->paragraph,
                'responded_by' => 2,
                'teknisi_id' => 3,
                'status_process' => $faker->randomElement(['Waiting', 'On Progress', 'Done']),
                'status_cso' => $faker->randomElement(['Waiting', 'On Progress', 'Done']),
                'status_teknisi' => $faker->randomElement(['Waiting', 'On Progress', 'Done']),
                'waktu_respon_cso' => now(),
                'waktu_respon_teknisi' => now(),
                'waktu_perjalanan' => now(),
                'waktu_tiba' => now(),
                'waktu_pengerjaan' => now(),
                'waktu_selesai' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // Insert data
            DB::table('customer_supports')->insert($customer);
        }
    }
}
