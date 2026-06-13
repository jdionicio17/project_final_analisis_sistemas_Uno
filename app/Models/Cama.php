<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cama extends Model
{
    use HasFactory;

    protected $table = 'camas';

    public const ESTADO_DISPONIBLE = 'disponible';
    public const ESTADO_OCUPADA = 'ocupada';
    public const ESTADO_MANTENIMIENTO = 'mantenimiento';

    protected $fillable = [
        'tenant_id',
        'codigo',
        'area',
        'ubicacion',
        'estado',
    ];

    public function admisiones(): HasMany
    {
        return $this->hasMany(Admision::class);
    }
}