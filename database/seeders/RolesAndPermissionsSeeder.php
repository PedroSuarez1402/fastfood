<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Resetear cache de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear Permisos
        // Dashboard
        Permission::create(['name' => 'ver_dashboard']);
        // Productos
        Permission::create(['name' => 'gestionar_productos']); // Crear, editar, borrar
        // Pedidos
        Permission::create(['name' => 'tomar_pedidos']);      // Crear pedido
        Permission::create(['name' => 'ver_cocina']);         // Ver pantalla KDS
        Permission::create(['name' => 'gestionar_pedidos']);  // Anular, cobrar
        // Usuarios y Config
        Permission::create(['name' => 'gestionar_usuarios']);
        Permission::create(['name' => 'gestionar_ajustes']);

        // 3. Crear y asignar roles y permisos

        // Mesero: solo toma pedidos y ve las mesas
        $role = Role::create(['name' => 'Mesero']);
        $role->givePermissionTo('tomar_pedidos');
        
        // Cocina: Solo ve la pantalla KDS
        $role = Role::create(['name' => 'Cocina']);
        $role->givePermissionTo('ver_cocina');

        // AdminRestaurante gestiona el negocio
        $role = Role::create(['name' => 'AdminRestaurante']);
        $role->givePermissionTo([
            'ver_dashboard',
            'gestionar_productos',
            'gestionar_pedidos',
            'gestionar_usuarios',
            'tomar_pedidos'
        ]);

        // D. SuperAdmin: Todo (incluyendo Appearance/Settings)
        $role = Role::create(['name' => 'SuperAdmin']);
        // SuperAdmin generalmente tiene un bypass, pero le asignamos todo por si acaso
        $role->givePermissionTo(Permission::all());
    }
}
