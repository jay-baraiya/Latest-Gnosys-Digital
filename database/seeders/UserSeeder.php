<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'id' => 1,
        ], [
            'email' => 'superadmin@gmail.com',
            'name' => 'Super Admin',
            'is_user' => 1,
            'password' => Hash::make('superadmin123'),
        ]);

        UserRole::updateOrCreate([
            'user_id' => 1,
            'role_id' => 1,
        ], [
            'user_id' => 1,
            'role_id' => 1,
        ]);
    }
}
