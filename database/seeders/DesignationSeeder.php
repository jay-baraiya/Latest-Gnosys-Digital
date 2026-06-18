<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            ['id' => 1, 'name' => 'Chairman', 'status' => 1],
            ['id' => 2, 'name' => 'Founder', 'status' => 1],
            ['id' => 3, 'name' => 'Co-Founder', 'status' => 1],
            ['id' => 4, 'name' => 'Chief Executive Officer (CEO)', 'status' => 1],
            ['id' => 5, 'name' => 'Chief Operating Officer (COO)', 'status' => 1],
            ['id' => 6, 'name' => 'Chief Technology Officer (CTO)', 'status' => 1],
            ['id' => 7, 'name' => 'Chief Financial Officer (CFO)', 'status' => 1],
            ['id' => 8, 'name' => 'Managing Director', 'status' => 1],
            ['id' => 9, 'name' => 'Director', 'status' => 1],
            ['id' => 10, 'name' => 'General Manager', 'status' => 1],
            ['id' => 11, 'name' => 'Operations Manager', 'status' => 1],
            ['id' => 12, 'name' => 'Project Manager', 'status' => 1],
            ['id' => 13, 'name' => 'Team Leader', 'status' => 1],
            ['id' => 14, 'name' => 'Technical Lead', 'status' => 1],
            ['id' => 15, 'name' => 'Solution Architect', 'status' => 1],
            ['id' => 16, 'name' => 'Software Engineer', 'status' => 1],
            ['id' => 17, 'name' => 'Software Developer', 'status' => 1],
            ['id' => 18, 'name' => 'Senior Software Developer', 'status' => 1],
            ['id' => 19, 'name' => 'Junior Software Developer', 'status' => 1],
            ['id' => 20, 'name' => 'Full Stack Developer', 'status' => 1],
            ['id' => 21, 'name' => 'Frontend Developer', 'status' => 1],
            ['id' => 22, 'name' => 'Backend Developer', 'status' => 1],
            ['id' => 23, 'name' => 'Laravel Developer', 'status' => 1],
            ['id' => 24, 'name' => 'PHP Developer', 'status' => 1],
            ['id' => 25, 'name' => 'React Developer', 'status' => 1],
            ['id' => 26, 'name' => 'Node.js Developer', 'status' => 1],
            ['id' => 27, 'name' => 'Mobile App Developer', 'status' => 1],
            ['id' => 28, 'name' => 'Android Developer', 'status' => 1],
            ['id' => 29, 'name' => 'iOS Developer', 'status' => 1],
            ['id' => 30, 'name' => 'DevOps Engineer', 'status' => 1],
            ['id' => 31, 'name' => 'System Administrator', 'status' => 1],
            ['id' => 32, 'name' => 'Network Engineer', 'status' => 1],
            ['id' => 33, 'name' => 'Database Administrator', 'status' => 1],
            ['id' => 34, 'name' => 'Cyber Security Analyst', 'status' => 1],
            ['id' => 35, 'name' => 'QA Engineer', 'status' => 1],
            ['id' => 36, 'name' => 'Software Tester', 'status' => 1],
            ['id' => 37, 'name' => 'UI/UX Designer', 'status' => 1],
            ['id' => 38, 'name' => 'Graphic Designer', 'status' => 1],
            ['id' => 39, 'name' => 'Product Manager', 'status' => 1],
            ['id' => 40, 'name' => 'Business Analyst', 'status' => 1],
            ['id' => 41, 'name' => 'Digital Marketing Manager', 'status' => 1],
            ['id' => 42, 'name' => 'Digital Marketing Executive', 'status' => 1],
            ['id' => 43, 'name' => 'SEO Manager', 'status' => 1],
            ['id' => 44, 'name' => 'SEO Specialist', 'status' => 1],
            ['id' => 45, 'name' => 'Content Writer', 'status' => 1],
            ['id' => 46, 'name' => 'Social Media Manager', 'status' => 1],
            ['id' => 47, 'name' => 'PPC Specialist', 'status' => 1],
            ['id' => 48, 'name' => 'Brand Manager', 'status' => 1],
            ['id' => 49, 'name' => 'Sales Executive', 'status' => 1],
            ['id' => 50, 'name' => 'Sales Manager', 'status' => 1],
            ['id' => 51, 'name' => 'Business Development Executive (BDE)', 'status' => 1],
            ['id' => 52, 'name' => 'Business Development Manager (BDM)', 'status' => 1],
            ['id' => 53, 'name' => 'Customer Support Executive', 'status' => 1],
            ['id' => 54, 'name' => 'Customer Success Manager', 'status' => 1],
            ['id' => 55, 'name' => 'Relationship Manager', 'status' => 1],
            ['id' => 56, 'name' => 'HR Executive', 'status' => 1],
            ['id' => 57, 'name' => 'HR Manager', 'status' => 1],
            ['id' => 58, 'name' => 'Recruiter', 'status' => 1],
            ['id' => 59, 'name' => 'Administrative Officer', 'status' => 1],
            ['id' => 60, 'name' => 'Office Manager', 'status' => 1],
            ['id' => 61, 'name' => 'Accountant', 'status' => 1],
            ['id' => 62, 'name' => 'Senior Accountant', 'status' => 1],
            ['id' => 63, 'name' => 'Accounts Executive', 'status' => 1],
            ['id' => 64, 'name' => 'Finance Manager', 'status' => 1],
            ['id' => 65, 'name' => 'Auditor', 'status' => 1],
            ['id' => 66, 'name' => 'Intern', 'status' => 1],
            ['id' => 67, 'name' => 'Trainee', 'status' => 1],
            ['id' => 68, 'name' => 'Associate Software Engineer', 'status' => 1],
            [
                'id' => 69,
                'name' => 'MERN Stack Developer',
                'status' => 1,
            ],
            [
                'id' => 70,
                'name' => 'MEAN Stack Developer',
                'status' => 1,
            ],
            [
                'id' => 71,
                'name' => 'Next.js Developer',
                'status' => 1,
            ],
            [
                'id' => 72,
                'name' => 'Vue.js Developer',
                'status' => 1,
            ],
            [
                'id' => 73,
                'name' => 'Angular Developer',
                'status' => 1,
            ],
            [
                'id' => 74,
                'name' => 'React Native Developer',
                'status' => 1,
            ],
            [
                'id' => 75,
                'name' => 'Flutter Developer',
                'status' => 1,
            ],
            [
                'id' => 76,
                'name' => 'Python Developer',
                'status' => 1,
            ],
            [
                'id' => 77,
                'name' => 'Django Developer',
                'status' => 1,
            ],
            [
                'id' => 78,
                'name' => 'Java Developer',
                'status' => 1,
            ],
            [
                'id' => 79,
                'name' => 'Spring Boot Developer',
                'status' => 1,
            ],
            [
                'id' => 80,
                'name' => '.NET Developer',
                'status' => 1,
            ],
            [
                'id' => 81,
                'name' => 'Go Developer',
                'status' => 1,
            ],
            [
                'id' => 82,
                'name' => 'Rust Developer',
                'status' => 1,
            ],
            [
                'id' => 83,
                'name' => 'Machine Learning Engineer',
                'status' => 1,
            ],
            [
                'id' => 84,
                'name' => 'AI Engineer',
                'status' => 1,
            ],
            [
                'id' => 85,
                'name' => 'Data Scientist',
                'status' => 1,
            ],
            [
                'id' => 86,
                'name' => 'Cloud Engineer',
                'status' => 1,
            ],
            [
                'id' => 87,
                'name' => 'AWS Engineer',
                'status' => 1,
            ],
            [
                'id' => 88,
                'name' => 'Azure Engineer',
                'status' => 1,
            ],
            [
                'id' => 89,
                'name' => 'Blockchain Developer',
                'status' => 1,
            ],
            [
                'id' => 90,
                'name' => 'WordPress Developer',
                'status' => 1,
            ]
        ];

        foreach ($designations as $designation) {
            Designation::updateOrInsert(
                ['id' => $designation['id']],
                [
                    'name' => $designation['name'],
                    'status' => $designation['status'],
                ]
            );
        }

    }
}
