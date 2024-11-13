<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HwInformation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hwInformation = [
            'hw_name' => 'IR-ADV C3520',
            'hw_type' => 'imageRUNNER',
            'hw_brand' => 'Canon',
            'hw_model' => 'IR-ADV C3520',
            'hw_serial_number' => '1234567890',
            'hw_image' => 'IR-ADV C3520.png',
            'hw_description' => 'Memperkenalkan perangkat multifungsi mono A4 yang ringkas dengan kapabilitas pemindaian super cepat dan konektivitas seluler terkini. Andal dan serbaguna, seri iR1643 menawarkan beragam luas fungsi yang disesuaikan agar selaras dengan tuntutan kelompok kerja yang sibuk.',
        ];

        DB::table('hw_informations')->insert($hwInformation);
    }
}
