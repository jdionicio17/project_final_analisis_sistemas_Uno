<?php

namespace Database\Seeders;

use App\Models\Cama;
use App\Models\Paciente;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdmisionHospitalariaSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = '00000000-0000-4000-8000-000000000001';

        Tenant::query()->firstOrCreate(
            ['id' => $tenantId],
            [
                'name' => 'Hospital Demo',
                'slug' => 'hospital-demo',
                'data' => [],
            ]
        );

        $user = User::query()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'email' => 'jose@demo.com',
            ],
            [
                'name' => 'Jose Dionicio',
                'password' => Hash::make('Password123'),
            ]
        );

        if (! $user->hasRole('Recepcionista')) {
            $user->assignRole('Recepcionista');
        }

        Paciente::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'documento' => 'P-001'],
            [
                'nombres' => 'Carlos Antonio',
                'apellidos' => 'Ramírez López',
                'fecha_nacimiento' => '1995-04-10',
                'sexo' => 'Masculino',
                'telefono' => '5555-0001',
                'direccion' => 'Guatemala',
            ]
        );

        Paciente::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'documento' => 'P-002'],
            [
                'nombres' => 'María Fernanda',
                'apellidos' => 'Gómez Pérez',
                'fecha_nacimiento' => '1988-09-22',
                'sexo' => 'Femenino',
                'telefono' => '5555-0002',
                'direccion' => 'Guatemala',
            ]
        );

        Paciente::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'documento' => 'P-003'],
            [
                'nombres' => 'José Alejandro',
                'apellidos' => 'Castillo Méndez',
                'fecha_nacimiento' => '2001-02-15',
                'sexo' => 'Masculino',
                'telefono' => '5555-0003',
                'direccion' => 'Guatemala',
            ]
        );

        Cama::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'codigo' => 'A-101'],
            [
                'area' => 'Medicina Interna',
                'ubicacion' => 'Nivel 1',
                'estado' => Cama::ESTADO_DISPONIBLE,
            ]
        );

        Cama::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'codigo' => 'A-102'],
            [
                'area' => 'Medicina Interna',
                'ubicacion' => 'Nivel 1',
                'estado' => Cama::ESTADO_DISPONIBLE,
            ]
        );

        Cama::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'codigo' => 'B-201'],
            [
                'area' => 'Emergencia',
                'ubicacion' => 'Nivel 2',
                'estado' => Cama::ESTADO_DISPONIBLE,
            ]
        );
    }
}