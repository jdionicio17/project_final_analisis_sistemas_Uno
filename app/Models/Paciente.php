<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    protected $fillable = [
        'tenant_id',
        'nombres',
        'apellidos',
        'documento',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
    ];

    public function admisiones(): HasMany
    {
        return $this->hasMany(Admision::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }
}