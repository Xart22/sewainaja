<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Customer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = [
            'name' => 'PT. ABC',
            'group_name' => 'Group ABC',
            'email' => NULL,
            'phone_number' => '081234567890',
            'address' => 'Jl. ABC No. 123',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'zip_code' => '12345',
            'country' => 'Indonesia',
            'latitude' => NULL,
            'longitude' => NULL,
            'pic_process' => 'ABC',
            'pic_process_phone_number' => '081234567890',
            'pic_installation' => 'DEF',
            'pic_installation_phone_number' => '081234567890',
            'pic_financial' => 'GHI',
            'pic_financial_phone_number' => '081234567890',
            'created_at' => now(),
        ];
    }
}
