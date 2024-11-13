<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAdmin = [
            'username' => 'admin',
            'name' => 'Admin',
            'role' => 'Admin',
            'password' => bcrypt('password'),
            'created_at' => now(),
        ];

        $userCso = [
            'username' => 'cso',
            'name' => 'CSO',
            'role' => 'CSO',
            'password' => bcrypt('password'),
            'created_at' => now(),
        ];

        $userTeknisi = [
            'username' => 'teknisi',
            'name' => 'Teknisi',
            'role' => 'Teknisi',
            'password' => bcrypt('password'),
            'created_at' => now(),
        ];

        // Insert data
        DB::table('users')->insert($userAdmin);
        DB::table('users')->insert($userCso);
        DB::table('users')->insert($userTeknisi);
    }
}
