<template>
    <section class="home">
        <h1 class="home__title">
            Sistema Hospitalario Integrado
        </h1>

        <p class="home__subtitle">
            Infraestructura base: Laravel 12, Vue 3, JWT y multitenancy por cabecera.
        </p>

        <div class="home__card">
            <h2 class="home__card-title">
                Módulo 8: Admisión hospitalaria
            </h2>

            <p class="home__card-text">
                Permite registrar el ingreso de un paciente, asociarlo con una cama disponible y actualizar la disponibilidad del recurso hospitalario.
            </p>

            <router-link class="home__link" :to="{ name: 'admisiones' }">
                Abrir módulo de admisión
            </router-link>
        </div>
    </section>
</template>

<script setup>
</script>

<style scoped>
.home {
    max-width: 760px;
}

.home__title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.home__subtitle {
    color: #475569;
    line-height: 1.6;
    margin-bottom: 1.25rem;
}

.home__card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.25rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}

.home__card-title {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.home__card-text {
    color: #475569;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.home__link {
    display: inline-flex;
    border-radius: 8px;
    background: #2563eb;
    color: #ffffff;
    padding: 0.7rem 1rem;
    text-decoration: none;
    font-weight: 600;
}
</style>