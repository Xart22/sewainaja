<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\User as SeedersUser;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            SeedersUser::class,
            Keperluan::class,
            Customer::class,
            HwInformation::class,
        ]);
    }
}
