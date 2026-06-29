<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users
            [
                'id' => 1,
                'name' => 'Create',
                'module' => 'Users',
                'slug' => 'create.users',
                'description' => 'Create Users',
                'status' => 1,
            ],
            [
                'id' => 2,
                'name' => 'View',
                'module' => 'Users',
                'slug' => 'view.users',
                'description' => 'View Users',
                'status' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Edit',
                'module' => 'Users',
                'slug' => 'edit.users',
                'description' => 'Edit Users',
                'status' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Delete',
                'module' => 'Users',
                'slug' => 'delete.users',
                'description' => 'Delete Users',
                'status' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Restore',
                'module' => 'Users',
                'slug' => 'restore.users',
                'description' => 'Restore Users',
                'status' => 1,
            ],

            // Roles
            [
                'id' => 6,
                'name' => 'Create',
                'module' => 'Roles',
                'slug' => 'create.roles',
                'description' => 'Create Roles',
                'status' => 1,
            ],
            [
                'id' => 7,
                'name' => 'View',
                'module' => 'Roles',
                'slug' => 'view.roles',
                'description' => 'View Roles',
                'status' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Edit',
                'module' => 'Roles',
                'slug' => 'edit.roles',
                'description' => 'Edit Roles',
                'status' => 1,
            ],
            [
                'id' => 9,
                'name' => 'Delete',
                'module' => 'Roles',
                'slug' => 'delete.roles',
                'description' => 'Delete Roles',
                'status' => 1,
            ],
            [
                'id' => 10,
                'name' => 'Restore',
                'module' => 'Roles',
                'slug' => 'restore.roles',
                'description' => 'Restore Roles',
                'status' => 1,
            ],

            // Categories
            [
                'id' => 11,
                'name' => 'Create',
                'module' => 'Categories',
                'slug' => 'create.categories',
                'description' => 'Create Categories',
                'status' => 1,
            ],
            [
                'id' => 12,
                'name' => 'View',
                'module' => 'Categories',
                'slug' => 'view.categories',
                'description' => 'View Categories',
                'status' => 1,
            ],
            [
                'id' => 13,
                'name' => 'Edit',
                'module' => 'Categories',
                'slug' => 'edit.categories',
                'description' => 'Edit Categories',
                'status' => 1,
            ],
            [
                'id' => 14,
                'name' => 'Delete',
                'module' => 'Categories',
                'slug' => 'delete.categories',
                'description' => 'Delete Categories',
                'status' => 1,
            ],
            [
                'id' => 15,
                'name' => 'Restore',
                'module' => 'Categories',
                'slug' => 'restore.categories',
                'description' => 'Restore Categories',
                'status' => 1,
            ],

            // Digital Products
            [
                'id' => 16,
                'name' => 'Create',
                'module' => 'Digital Products',
                'slug' => 'create.digital.products',
                'description' => 'Create Digital Products',
                'status' => 1,
            ],
            [
                'id' => 17,
                'name' => 'View',
                'module' => 'Digital Products',
                'slug' => 'view.digital.products',
                'description' => 'View Digital Products',
                'status' => 1,
            ],
            [
                'id' => 18,
                'name' => 'Edit',
                'module' => 'Digital Products',
                'slug' => 'edit.digital.products',
                'description' => 'Edit Digital Products',
                'status' => 1,
            ],
            [
                'id' => 19,
                'name' => 'Delete',
                'module' => 'Digital Products',
                'slug' => 'delete.digital.products',
                'description' => 'Delete Digital Products',
                'status' => 1,
            ],
            [
                'id' => 20,
                'name' => 'Restore',
                'module' => 'Digital Products',
                'slug' => 'restore.digital.products',
                'description' => 'Restore Digital Products',
                'status' => 1,
            ],

            // Digital Services
            [
                'id' => 21,
                'name' => 'Create',
                'module' => 'Digital Services',
                'slug' => 'create.digital.services',
                'description' => 'Create Digital Services',
                'status' => 1,
            ],
            [
                'id' => 22,
                'name' => 'View',
                'module' => 'Digital Services',
                'slug' => 'view.digital.services',
                'description' => 'View Digital Services',
                'status' => 1,
            ],
            [
                'id' => 23,
                'name' => 'Edit',
                'module' => 'Digital Services',
                'slug' => 'edit.digital.services',
                'description' => 'Edit Digital Services',
                'status' => 1,
            ],
            [
                'id' => 24,
                'name' => 'Delete',
                'module' => 'Digital Services',
                'slug' => 'delete.digital.services',
                'description' => 'Delete Digital Services',
                'status' => 1,
            ],
            [
                'id' => 25,
                'name' => 'Restore',
                'module' => 'Digital Services',
                'slug' => 'restore.digital.services',
                'description' => 'Restore Digital Services',
                'status' => 1,
            ],

            // Blogs
            [
                'id' => 26,
                'name' => 'Create',
                'module' => 'Blogs',
                'slug' => 'create.blogs',
                'description' => 'Create Blogs',
                'status' => 1,
            ],
            [
                'id' => 27,
                'name' => 'View',
                'module' => 'Blogs',
                'slug' => 'view.blogs',
                'description' => 'View Blogs',
                'status' => 1,
            ],
            [
                'id' => 28,
                'name' => 'Edit',
                'module' => 'Blogs',
                'slug' => 'edit.blogs',
                'description' => 'Edit Blogs',
                'status' => 1,
            ],
            [
                'id' => 29,
                'name' => 'Delete',
                'module' => 'Blogs',
                'slug' => 'delete.blogs',
                'description' => 'Delete Blogs',
                'status' => 1,
            ],
            [
                'id' => 30,
                'name' => 'Restore',
                'module' => 'Blogs',
                'slug' => 'restore.blogs',
                'description' => 'Restore Blogs',
                'status' => 1,
            ],

            // Wallets
            [
                'id' => 31,
                'name' => 'Create',
                'module' => 'Wallets',
                'slug' => 'create.wallets',
                'description' => 'Create Wallets',
                'status' => 1,
            ],
            [
                'id' => 32,
                'name' => 'View',
                'module' => 'Wallets',
                'slug' => 'view.wallets',
                'description' => 'View Wallets',
                'status' => 1,
            ],
            [
                'id' => 33,
                'name' => 'Edit',
                'module' => 'Wallets',
                'slug' => 'edit.wallets',
                'description' => 'Edit Wallets',
                'status' => 1,
            ],
            [
                'id' => 34,
                'name' => 'Delete',
                'module' => 'Wallets',
                'slug' => 'delete.wallets',
                'description' => 'Delete Wallets',
                'status' => 1,
            ],
            [
                'id' => 35,
                'name' => 'Approve',
                'module' => 'Wallets',
                'slug' => 'approve.wallets',
                'description' => 'Approve Wallets',
                'status' => 1,
            ],
            [
                'id' => 36,
                'name' => 'Reject',
                'module' => 'Wallets',
                'slug' => 'reject.wallets',
                'description' => 'Reject Wallets',
                'status' => 1,
            ],

            // Tickets
            [
                'id' => 37,
                'name' => 'Create',
                'module' => 'Tickets',
                'slug' => 'create.tickets',
                'description' => 'Create Tickets',
                'status' => 1,
            ],
            [
                'id' => 38,
                'name' => 'View',
                'module' => 'Tickets',
                'slug' => 'view.tickets',
                'description' => 'View Tickets',
                'status' => 1,
            ],
            [
                'id' => 39,
                'name' => 'Edit',
                'module' => 'Tickets',
                'slug' => 'edit.tickets',
                'description' => 'Edit Tickets',
                'status' => 1,
            ],
            [
                'id' => 40,
                'name' => 'Delete',
                'module' => 'Tickets',
                'slug' => 'delete.tickets',
                'description' => 'Delete Tickets',
                'status' => 1,
            ],

            //orders

            [
                'id' => 41,
                'name' => 'Create',
                'module' => 'Orders',
                'slug' => 'create.orders',
                'description' => 'Create Orders',
                'status' => 1,
            ],
            [
                'id' => 42,
                'name' => 'View',
                'module' => 'Orders',
                'slug' => 'view.orders',
                'description' => 'View Orders',
                'status' => 1,
            ],
            [
                'id' => 43,
                'name' => 'Edit',
                'module' => 'Orders',
                'slug' => 'edit.orders',
                'description' => 'Edit Orders',
                'status' => 1,
            ],
            [
                'id' => 44,
                'name' => 'Delete',
                'module' => 'Orders',
                'slug' => 'delete.orders',
                'description' => 'Delete Orders',
                'status' => 1,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['id' => $permission['id']], $permission);
        }
    }
}
