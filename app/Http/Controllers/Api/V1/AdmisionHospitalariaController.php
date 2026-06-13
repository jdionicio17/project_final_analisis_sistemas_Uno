<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admision;
use App\Models\Cama;
use App\Models\Paciente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdmisionHospitalariaController extends Controller
{
    public function catalogos(Request $request): JsonResponse
    {
        $tenantId = $this->tenantId($request);

        $pacientes = Paciente::query()
            ->where('tenant_id', $tenantId)
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();

        $camas = Cama::query()
            ->where('tenant_id', $tenantId)
            ->where('estado', Cama::ESTADO_DISPONIBLE)
            ->orderBy('codigo')
            ->get();

        return response()->json([
            'pacientes' => $pacientes,
            'camas_disponibles' => $camas,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $tenantId = $this->tenantId($request);

        $admisiones = Admision::query()
            ->with(['paciente', 'cama', 'usuario'])
            ->where('tenant_id', $tenantId)
            ->latest('fecha_admision')
            ->get();

        return response()->json([
            'admisiones' => $admisiones,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $tenantId = $this->tenantId($request);

        $validated = $request->validate([
            'paciente_id' => ['required', 'integer'],
            'cama_id' => ['required', 'integer'],
            'fecha_admision' => ['required', 'date'],
            'motivo' => ['required', 'string', 'max:2000'],
            'diagnostico_ingreso' => ['nullable', 'string', 'max:2000'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $paciente = Paciente::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($validated['paciente_id'])
            ->first();

        if ($paciente === null) {
            throw ValidationException::withMessages([
                'paciente_id' => ['El paciente seleccionado no existe para este tenant.'],
            ]);
        }

        $admision = DB::transaction(function () use ($validated, $tenantId): Admision {
            $cama = Cama::query()
                ->where('tenant_id', $tenantId)
                ->whereKey($validated['cama_id'])
                ->lockForUpdate()
                ->first();

            if ($cama === null) {
                throw ValidationException::withMessages([
                    'cama_id' => ['La cama seleccionada no existe para este tenant.'],
                ]);
            }

            if ($cama->estado !== Cama::ESTADO_DISPONIBLE) {
                throw ValidationException::withMessages([
                    'cama_id' => ['La cama seleccionada ya no se encuentra disponible.'],
                ]);
            }

            $nuevaAdmision = Admision::query()->create([
                'tenant_id' => $tenantId,
                'paciente_id' => $validated['paciente_id'],
                'cama_id' => $validated['cama_id'],
                'usuario_id' => auth('api')->id(),
                'fecha_admision' => $validated['fecha_admision'],
                'motivo' => $validated['motivo'],
                'diagnostico_ingreso' => $validated['diagnostico_ingreso'] ?? null,
                'estado' => Admision::ESTADO_ACTIVA,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            $cama->update([
                'estado' => Cama::ESTADO_OCUPADA,
            ]);

            return $nuevaAdmision;
        });

        return response()->json([
            'message' => 'Admisión hospitalaria registrada correctamente.',
            'admision' => $admision->load(['paciente', 'cama', 'usuario']),
        ], 201);
    }

    public function show(Request $request, Admision $admision): JsonResponse
    {
        $this->validarTenant($request, $admision);

        return response()->json([
            'admision' => $admision->load(['paciente', 'cama', 'usuario']),
        ]);
    }

    public function cancelar(Request $request, Admision $admision): JsonResponse
    {
        $this->validarTenant($request, $admision);

        $validated = $request->validate([
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($admision->estado !== Admision::ESTADO_ACTIVA) {
            throw ValidationException::withMessages([
                'estado' => ['Solo se puede cancelar una admisión activa.'],
            ]);
        }

        DB::transaction(function () use ($admision, $validated): void {
            $admision->update([
                'estado' => Admision::ESTADO_CANCELADA,
                'observaciones' => $validated['observaciones'] ?? $admision->observaciones,
            ]);

            Cama::query()
                ->whereKey($admision->cama_id)
                ->update([
                    'estado' => Cama::ESTADO_DISPONIBLE,
                ]);
        });

        return response()->json([
            'message' => 'Admisión cancelada y cama liberada correctamente.',
            'admision' => $admision->fresh(['paciente', 'cama', 'usuario']),
        ]);
    }

    private function validarTenant(Request $request, Admision $admision): void
    {
        $tenantId = $this->tenantId($request);

        if ((string) $admision->tenant_id !== $tenantId) {
            abort(404);
        }
    }

    private function tenantId(Request $request): string
    {
        return (string) data_get($request->attributes->get('tenant'), 'id');
    }
}