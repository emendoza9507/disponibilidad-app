<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = Role::create(['name' => 'admin']);
        $tecnico = Role::create(['name'=>'tecnico']);

        Permission::create(['name' => 'connections.index'])->assignRole($admin);
        Permission::create(['name' => 'new_connection'])->assignRole($admin);
        Permission::create(['name' => 'users.index'])->assignRole($admin);

        Permission::create(['name' => 'autos.index'])->syncRoles([$admin, $tecnico]);
        Permission::create(['name' => 'neumatico.index'])->syncRoles([$admin, $tecnico]);
    }
}
