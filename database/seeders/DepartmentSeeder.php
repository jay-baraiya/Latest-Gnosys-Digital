<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'id' => 1,
                'name' => 'Support',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Sales & Business Development',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Maintenance',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Technical Support Department',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Project Delivery',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Digital Marketing',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Client Success',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Billing & Finance',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 9,
                'name' => 'Quality Assurance',
                'description' => null,
                'status' => 1,
            ],
            [
                'id' => 10,
                'name' => 'R&D & Innovation',
                'description' => null,
                'status' => 1,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['id' => $department['id']],
                $department
            );
        }
    }
}
