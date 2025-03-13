<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario administrador
        User::create([
            'name' => 'Administrador General',
            'email' => 'administrador@example.com',
            'password' => bcrypt('12345678'), // Contraseña: 12345678
            'role_id' => 1, // ID del rol 'admin'
            'direccion' => 'Calle Principal #123',
            'telefono' => '3006631999',
            'estado' => 'activo',
        ]);
    }
}
