<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Restaurante']);
        Role::create(['name' => 'Cliente']);

        // Crear permisos y asignarlos a roles
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage restaurants']);
        Permission::create(['name' => 'manage reservations']);
        Permission::create(['name' => 'make reservations']);

        $adminRole = Role::findByName('Admin');
        $adminRole->givePermissionTo(['manage users', 'manage restaurants']);

        $restaurantRole = Role::findByName('Restaurante');
        $restaurantRole->givePermissionTo('manage reservations');

        $clientRole = Role::findByName('Cliente');
        $clientRole->givePermissionTo('make reservations');
        
    }
}
