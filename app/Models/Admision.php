<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admision extends Model
{
    use HasFactory;

    protected $table = 'admisiones';

    protected $casts = [
        'fecha_admision' => 'datetime',
    ];

    public const ESTADO_ACTIVA = 'activa';
    public const ESTADO_CANCELADA = 'cancelada';

    protected $fillable = [
        'tenant_id',
        'paciente_id',
        'cama_id',
        'usuario_id',
        'fecha_admision',
        'motivo',
        'diagnostico_ingreso',
        'estado',
        'observaciones',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function cama(): BelongsTo
    {
        return $this->belongsTo(Cama::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}