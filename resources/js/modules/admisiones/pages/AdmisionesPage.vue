<template>
    <section class="admisiones">
        <header class="admisiones__header">
            <div>
                <h1 class="admisiones__title">
                    Admisión hospitalaria
                </h1>
                <p class="admisiones__subtitle">
                    Registro de ingreso de paciente y asignación de cama disponible.
                </p>
            </div>

            <button class="admisiones__reload" type="button" @click="loadData">
                Actualizar
            </button>
        </header>

        <p v-if="message" class="admisiones__message">
            {{ message }}
        </p>

        <p v-if="errorMessage" class="admisiones__error">
            {{ errorMessage }}
        </p>

        <div class="admisiones__grid">
            <form class="admisiones__card" @submit.prevent="submitAdmission">
                <h2 class="admisiones__section-title">
                    Nueva admisión
                </h2>

                <label class="admisiones__label">
                    Paciente
                    <select v-model="form.paciente_id" class="admisiones__input" required>
                        <option value="">
                            Seleccione paciente
                        </option>
                        <option
                            v-for="paciente in pacientes"
                            :key="paciente.id"
                            :value="paciente.id"
                        >
                            {{ paciente.nombres }} {{ paciente.apellidos }} - {{ paciente.documento }}
                        </option>
                    </select>
                </label>

                <label class="admisiones__label">
                    Cama disponible
                    <select v-model="form.cama_id" class="admisiones__input" required>
                        <option value="">
                            Seleccione cama
                        </option>
                        <option
                            v-for="cama in camasDisponibles"
                            :key="cama.id"
                            :value="cama.id"
                        >
                            {{ cama.codigo }} - {{ cama.area }} - {{ cama.ubicacion }}
                        </option>
                    </select>
                </label>

                <label class="admisiones__label">
                    Fecha de admisión
                    <input
                        v-model="form.fecha_admision"
                        class="admisiones__input"
                        type="datetime-local"
                        required
                    >
                </label>

                <label class="admisiones__label">
                    Motivo de ingreso
                    <textarea
                        v-model="form.motivo"
                        class="admisiones__textarea"
                        required
                        rows="3"
                    />
                </label>

                <label class="admisiones__label">
                    Diagnóstico de ingreso
                    <textarea
                        v-model="form.diagnostico_ingreso"
                        class="admisiones__textarea"
                        rows="3"
                    />
                </label>

                <label class="admisiones__label">
                    Observaciones
                    <textarea
                        v-model="form.observaciones"
                        class="admisiones__textarea"
                        rows="3"
                    />
                </label>

                <button class="admisiones__submit" type="submit" :disabled="saving">
                    {{ saving ? 'Guardando...' : 'Registrar admisión' }}
                </button>
            </form>

            <article class="admisiones__card">
                <h2 class="admisiones__section-title">
                    Admisiones registradas
                </h2>

                <p v-if="loading" class="admisiones__empty">
                    Cargando admisiones...
                </p>

                <p v-else-if="admisiones.length === 0" class="admisiones__empty">
                    No hay admisiones registradas.
                </p>

                <div v-else class="admisiones__list">
                    <article
                        v-for="admision in admisiones"
                        :key="admision.id"
                        class="admisiones__item"
                    >
                        <div class="admisiones__item-header">
                            <strong>
                                {{ admision.paciente?.nombres }} {{ admision.paciente?.apellidos }}
                            </strong>
                            <span :class="['admisiones__badge', `admisiones__badge--${admision.estado}`]">
                                {{ admision.estado }}
                            </span>
                        </div>

                        <p>
                            <strong>Cama:</strong>
                            {{ admision.cama?.codigo }} - {{ admision.cama?.area }}
                        </p>

                        <p>
                            <strong>Fecha:</strong>
                            {{ formatDate(admision.fecha_admision) }}
                        </p>

                        <p>
                            <strong>Motivo:</strong>
                            {{ admision.motivo }}
                        </p>

                        <p v-if="admision.diagnostico_ingreso">
                            <strong>Diagnóstico:</strong>
                            {{ admision.diagnostico_ingreso }}
                        </p>

                        <button
                            v-if="admision.estado === 'activa'"
                            class="admisiones__cancel"
                            type="button"
                            @click="cancelAdmission(admision)"
                        >
                            Cancelar y liberar cama
                        </button>
                    </article>
                </div>
            </article>
        </div>
    </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import api from '@/plugins/axios';

const pacientes = ref([]);
const camasDisponibles = ref([]);
const admisiones = ref([]);
const loading = ref(false);
const saving = ref(false);
const message = ref('');
const errorMessage = ref('');

const form = reactive({
    paciente_id: '',
    cama_id: '',
    fecha_admision: getDefaultDateTime(),
    motivo: '',
    diagnostico_ingreso: '',
    observaciones: '',
});

function getDefaultDateTime() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

    return now.toISOString().slice(0, 16);
}

function resetForm() {
    form.paciente_id = '';
    form.cama_id = '';
    form.fecha_admision = getDefaultDateTime();
    form.motivo = '';
    form.diagnostico_ingreso = '';
    form.observaciones = '';
}

async function loadData() {
    loading.value = true;
    errorMessage.value = '';

    try {
        const [catalogosResponse, admisionesResponse] = await Promise.all([
            api.get('/admisiones/catalogos'),
            api.get('/admisiones'),
        ]);

        pacientes.value = catalogosResponse.data.pacientes ?? [];
        camasDisponibles.value = catalogosResponse.data.camas_disponibles ?? [];
        admisiones.value = admisionesResponse.data.admisiones ?? [];
    } catch (error) {
        errorMessage.value = error?.response?.data?.message
            ?? 'No fue posible cargar el módulo de admisiones.';
    } finally {
        loading.value = false;
    }
}

async function submitAdmission() {
    saving.value = true;
    message.value = '';
    errorMessage.value = '';

    try {
        const { data } = await api.post('/admisiones', {
            paciente_id: form.paciente_id,
            cama_id: form.cama_id,
            fecha_admision: form.fecha_admision,
            motivo: form.motivo,
            diagnostico_ingreso: form.diagnostico_ingreso,
            observaciones: form.observaciones,
        });

        message.value = data.message ?? 'Admisión registrada correctamente.';
        resetForm();
        await loadData();
    } catch (error) {
        const errors = error?.response?.data?.errors;

        errorMessage.value = errors
            ? Object.values(errors).flat().join(' ')
            : error?.response?.data?.message ?? 'No fue posible registrar la admisión.';
    } finally {
        saving.value = false;
    }
}

async function cancelAdmission(admision) {
    message.value = '';
    errorMessage.value = '';

    try {
        const { data } = await api.post(`/admisiones/${admision.id}/cancelar`, {
            observaciones: 'Cancelación realizada desde el módulo de admisión hospitalaria.',
        });

        message.value = data.message ?? 'Admisión cancelada correctamente.';
        await loadData();
    } catch (error) {
        errorMessage.value = error?.response?.data?.message
            ?? 'No fue posible cancelar la admisión.';
    }
}

function formatDate(value) {
    if (! value) {
        return 'Sin fecha';
    }

    return new Intl.DateTimeFormat('es-GT', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(value));
}

onMounted(loadData);
</script>

<style scoped>
.admisiones {
    max-width: 1180px;
    margin: 0 auto;
}

.admisiones__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.admisiones__title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.35rem;
}

.admisiones__subtitle {
    color: #475569;
}

.admisiones__reload,
.admisiones__submit,
.admisiones__cancel {
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.admisiones__reload {
    background: #e2e8f0;
    color: #0f172a;
    padding: 0.65rem 1rem;
}

.admisiones__grid {
    display: grid;
    grid-template-columns: minmax(320px, 420px) 1fr;
    gap: 1.25rem;
}

.admisiones__card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.25rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}

.admisiones__section-title {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.admisiones__label {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    margin-bottom: 0.8rem;
    color: #334155;
    font-size: 0.9rem;
}

.admisiones__input,
.admisiones__textarea {
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.65rem 0.75rem;
    font-size: 0.95rem;
    resize: vertical;
}

.admisiones__submit {
    width: 100%;
    background: #2563eb;
    color: #ffffff;
    padding: 0.8rem 1rem;
}

.admisiones__submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.admisiones__message {
    background: #dcfce7;
    border: 1px solid #86efac;
    color: #166534;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}

.admisiones__error {
    background: #fee2e2;
    border: 1px solid #fca5a5;
    color: #991b1b;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}

.admisiones__empty {
    color: #64748b;
}

.admisiones__list {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.admisiones__item {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.9rem;
    background: #f8fafc;
}

.admisiones__item p {
    margin: 0.35rem 0;
    color: #334155;
}

.admisiones__item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.admisiones__badge {
    border-radius: 999px;
    padding: 0.2rem 0.6rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.admisiones__badge--activa {
    background: #dbeafe;
    color: #1d4ed8;
}

.admisiones__badge--cancelada {
    background: #fee2e2;
    color: #b91c1c;
}

.admisiones__cancel {
    margin-top: 0.65rem;
    background: #fee2e2;
    color: #991b1b;
    padding: 0.55rem 0.8rem;
}

@media (max-width: 900px) {
    .admisiones__grid {
        grid-template-columns: 1fr;
    }

    .admisiones__header {
        flex-direction: column;
    }
}
</style>