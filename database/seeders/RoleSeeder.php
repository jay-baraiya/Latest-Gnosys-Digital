<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'Super Admin', 'slug' => 'super-admin', 'status' => '1'],
            ['id' => 2, 'name' => 'Manager', 'slug' => 'manager', 'status' => '1'],
            ['id' => 3, 'name' => 'Client', 'slug' => 'client', 'status' => '1'],
            ['id' => 4, 'name' => 'Student', 'slug' => 'student', 'status' => '1'],
            ['id' => 5, 'name' => 'Visitor', 'slug' => 'visitor', 'status' => '1'],
            ['id' => 6, 'name' => 'Professor', 'slug' => 'professor', 'status' => '1'],
            ['id' => 7, 'name' => 'Buyer', 'slug' => 'buyer', 'status' => '1'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
