<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'placa',
        'color',
        'marca',
        'tipo_vehiculo',
        'conductor_id',
        'propietario_id'
    ];

    protected $casts = [
        'conductor_id' => 'integer',
        'propietario_id' => 'integer',
    ];

    public function conductor() {
        return $this->belongsTo(Conductor::class);
    }
    public function propietario() {
        return $this->belongsTo(Propietario::class);
    }
}
