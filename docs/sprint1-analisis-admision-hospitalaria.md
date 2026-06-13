# Sprint 1 - Análisis del módulo de admisión hospitalaria

## Estudiante
José Alexánder Dionicio Córdova

## Módulo asignado
Módulo 8: Admisión hospitalaria.

## Repositorio trabajado
https://github.com/jdionicio17/project_final_analisis_sistemas_Uno.git

## Rama de trabajo
main

## Arquitectura base identificada
El proyecto base corresponde a un sistema hospitalario desarrollado con Laravel 12 en backend y Vue 3 con Vite en frontend.

El backend trabaja mediante rutas API protegidas por middleware de tenant y autenticación JWT. El frontend consume dichas rutas mediante Axios, enviando el token de sesión y la cabecera X-Tenant-ID.

## Base de datos
La base de datos se trabajará en PostgreSQL usando pgAdmin. La conexión se configura en el archivo .env mediante DB_CONNECTION=pgsql.

## Objetivo del módulo
Implementar el flujo de admisión hospitalaria, permitiendo registrar el ingreso de un paciente y asociarlo con una cama disponible.

## Alcance funcional
- Consultar pacientes disponibles para admisión.
- Consultar camas disponibles.
- Registrar una admisión hospitalaria.
- Cambiar automáticamente el estado de la cama a ocupada.
- Consultar el listado de admisiones registradas.
- Cancelar una admisión activa y liberar la cama asociada.

## Decisión humana tomada
Se decidió implementar el módulo de admisión hospitalaria como una funcionalidad independiente pero relacionada con pacientes y camas, debido a que el módulo requiere asociar paciente y cama para completar correctamente el flujo solicitado por el docente.

## Verificación aplicada
Se verificó que el proyecto cargara correctamente en Laravel, que Composer instalara las dependencias, que PostgreSQL recibiera las migraciones base y que la pantalla de login estuviera disponible.
