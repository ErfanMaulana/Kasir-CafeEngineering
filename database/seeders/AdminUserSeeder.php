<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@engineeringcafe.unsil.ac.id'],
            [
                'name' => 'Engineering Cafe Admin',
                'email' => 'admin@engineeringcafe.unsil.ac.id',
                'password' => Hash::make('3ngin33rinG'),
            ]
        );
    }
}
