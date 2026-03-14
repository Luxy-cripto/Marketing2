<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Marketing',
            'email' => 'marketing@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'marketing'
        ]);
    }
}
