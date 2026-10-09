<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Support\Permissions;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permissions::all() as $slug) {
            Permission::updateOrCreate(
                ['slug' => $slug],
                ['name' => Permissions::labelOf($slug), 'group' => Permissions::groupOf($slug)],
            );
        }

        $admin = Role::updateOrCreate(
            ['slug' => Role::ADMIN],
            ['name' => 'Administrator', 'description' => 'Full access to every part of the system.'],
        );

        $consultant = Role::updateOrCreate(
            ['slug' => Role::CONSULTANT],
            ['name' => 'Consultant', 'description' => 'Works the students assigned to them.'],
        );

        $student = Role::updateOrCreate(
            ['slug' => Role::STUDENT],
            ['name' => 'Student', 'description' => 'Portal access to their own record only.'],
        );

        // Admin gets every permission row as well as the implicit bypass, so the
        // admin permission grid shows the truth rather than an empty list.
        $admin->permissions()->sync(Permission::pluck('id'));

        $consultant->permissions()->sync(
            Permission::whereIn('slug', Permissions::CONSULTANT)->pluck('id')
        );

        $student->permissions()->sync(
            Permission::whereIn('slug', Permissions::STUDENT)->pluck('id')
        );
    }
}
