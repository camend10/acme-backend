<?php

namespace App\Services\Generales;

use App\Models\Conductor;
use App\Models\Propietario;
use App\Models\User;
use App\Models\Role;
use App\Models\Vehiculo;

class GeneralService
{

    public function roles()
    {
        return Role::all();
    }

    public function conductores()
    {
        return Conductor::all();
    }

    public function propietarios()
    {
        return Propietario::all();
    }

    public function vehiculos()
    {
        return Vehiculo::all();
    }

    public function usuarios()
    {
        return User::where('estado', 'activo')->get();
    }
}
