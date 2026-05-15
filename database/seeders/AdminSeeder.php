<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'full_name' => 'Admin Perpustakaan',
            'email'    => 'admin@perpus.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);
    }
}
