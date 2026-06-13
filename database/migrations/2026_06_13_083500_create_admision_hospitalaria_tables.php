<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pacientes')) {
            Schema::create('pacientes', function (Blueprint $table): void {
                $table->id();
                $table->string('tenant_id');
                $table->string('nombres');
                $table->string('apellidos');
                $table->string('documento')->nullable();
                $table->date('fecha_nacimiento')->nullable();
                $table->string('sexo', 20)->nullable();
                $table->string('telefono')->nullable();
                $table->text('direccion')->nullable();
                $table->timestamps();

                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                $table->unique(['tenant_id', 'documento']);
            });
        }

        if (! Schema::hasTable('camas')) {
            Schema::create('camas', function (Blueprint $table): void {
                $table->id();
                $table->string('tenant_id');
                $table->string('codigo');
                $table->string('area')->nullable();
                $table->string('ubicacion')->nullable();
                $table->string('estado')->default('disponible');
                $table->timestamps();

                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                $table->unique(['tenant_id', 'codigo']);
            });
        }

        if (! Schema::hasTable('admisiones')) {
            Schema::create('admisiones', function (Blueprint $table): void {
                $table->id();
                $table->string('tenant_id');
                $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
                $table->foreignId('cama_id')->constrained('camas')->cascadeOnDelete();
                $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
                $table->dateTime('fecha_admision');
                $table->text('motivo');
                $table->text('diagnostico_ingreso')->nullable();
                $table->string('estado')->default('activa');
                $table->text('observaciones')->nullable();
                $table->timestamps();

                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                $table->index(['tenant_id', 'estado']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admisiones');
        Schema::dropIfExists('camas');
        Schema::dropIfExists('pacientes');
    }
};