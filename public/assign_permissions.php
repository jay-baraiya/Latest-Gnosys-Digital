<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;

$role = Role::find(1);
if ($role) {
    $role->permissions()->sync(Permission::pluck('id'));
    echo "Permissions assigned successfully to role 1!\n";
} else {
    echo "Role not found.\n";
}
