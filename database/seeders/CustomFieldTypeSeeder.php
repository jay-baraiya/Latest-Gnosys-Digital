<?php

namespace Database\Seeders;

use App\Models\CustomFieldType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomFieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'id' => 1,
                'name' => 'Single Text',
                'key' => 'text',
                'has_options' => false,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                    'placeholder' => null,
                    'default_value' => null,
                ],
            ],
            [
                'id' => 2,
                'name' => 'Long Text (Textarea)',
                'key' => 'textarea',
                'has_options' => false,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                    'placeholder' => null,
                    'default_value' => null,
                    'rows' => 4,
                ],
            ],
            [
                'id' => 3,
                'name' => 'Number',
                'key' => 'number',
                'has_options' => false,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                    'placeholder' => null,
                    'default_value' => null,
                    'min' => null,
                    'max' => null,
                ],
            ],
            [
                'id' => 4,
                'name' => 'Email Address',
                'key' => 'email',
                'has_options' => false,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                    'is_email' => true,
                    'placeholder' => null,
                    'default_value' => null,
                ],
            ],
            [
                'id' => 5,
                'name' => 'Dropdown (Select)',
                'key' => 'select',
                'has_options' => true,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                    'multiple' => false,
                ],
            ],
            [
                'id' => 6,
                'name' => 'Checkbox',
                'key' => 'checkbox',
                'has_options' => true,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                ],
            ],
            [
                'id' => 7,
                'name' => 'Radio Button',
                'key' => 'radio',
                'has_options' => true,
                'status' => 1,
                'params' => [
                    'is_required' => false,
                ],
            ],
        ];

        foreach ($types as $type) {
            CustomFieldType::updateOrCreate(
                ['id' => $type['id']],
                [
                    'name' => $type['name'],
                    'key' => $type['key'],
                    'has_options' => $type['has_options'],
                    'status' => $type['status'],
                    'params' => json_encode($type['params']),
                ]
            );
        }
    }
}
