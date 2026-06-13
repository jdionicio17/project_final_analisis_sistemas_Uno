## Módulo 8: Admisión hospitalaria

### Estudiante

José Alexánder Dionicio Córdova

### Carné

1890-23-15373

### Repositorio trabajado

https://github.com/jdionicio17/project_final_analisis_sistemas_Uno

### Rama utilizada

main

### Descripción general del módulo

El módulo de admisión hospitalaria permite registrar el ingreso de un paciente al sistema hospitalario, asociarlo con una cama disponible y dejar trazabilidad básica del proceso. Al registrar una admisión, la cama seleccionada cambia automáticamente a estado ocupada. Si la admisión se cancela, la cama vuelve a quedar disponible.

---

# Desarrollo por sprints

## Sprint 1: Análisis, arquitectura y planificación del módulo

### Objetivo del sprint

Analizar la estructura base del proyecto, identificar el stack tecnológico y definir el alcance funcional del módulo asignado.

### Actividades realizadas

* Se revisó el repositorio base del proyecto.
* Se confirmó que el proyecto trabaja con Laravel 12 en backend.
* Se identificó el uso de Vue 3 y Vite en frontend.
* Se verificó el uso de rutas API bajo `/api/v1`.
* Se configuró el entorno local con PHP, Composer, Node.js y PostgreSQL.
* Se confirmó que la base de datos sería trabajada en PostgreSQL mediante pgAdmin.
* Se identificó el uso de autenticación JWT.
* Se identificó el uso de cabecera `X-Tenant-ID` para trabajar con tenant.
* Se definió el alcance funcional del módulo 8: Admisión hospitalaria.

### Decisión humana tomada

Se decidió desarrollar el módulo de admisión como una funcionalidad relacionada con pacientes y camas, ya que una admisión hospitalaria necesita asociar un paciente con un recurso físico disponible dentro del hospital.

### Resultado del sprint

Se dejó preparado el entorno, se comprendió la arquitectura del proyecto y se definió la estructura funcional que se implementaría en el Sprint 2.

### Commit asociado

`docs: analizar arquitectura base y modulo de admision hospitalaria`

---

## Sprint 2: Implementación funcional del módulo de admisión hospitalaria

### Objetivo del sprint

Implementar la funcionalidad principal del módulo, integrando backend, base de datos y frontend.

### Actividades realizadas

* Se creó la migración para las tablas `pacientes`, `camas` y `admisiones`.
* Se creó el modelo `Paciente`.
* Se creó el modelo `Cama`.
* Se creó el modelo `Admision`.
* Se creó el controlador `AdmisionHospitalariaController`.
* Se agregaron rutas protegidas en `routes/api.php`.
* Se creó un seeder con datos de prueba para pacientes, camas y usuario.
* Se implementó la vista Vue `AdmisionesPage.vue`.
* Se agregó la ruta frontend `/admisiones`.
* Se agregó acceso al módulo desde la página principal.
* Se corrigieron errores de codificación UTF-8 y nombres de tablas en los modelos.
* Se verificó que el módulo registrara admisiones correctamente.

### Funcionalidades implementadas

* Listado de admisiones hospitalarias.
* Consulta de pacientes.
* Consulta de camas disponibles.
* Registro de nueva admisión.
* Cambio automático de cama a estado ocupada.
* Cancelación de admisión.
* Liberación automática de cama al cancelar.
* Visualización de estado activo/cancelado.

### Archivos principales modificados o creados

* `database/migrations/2026_06_13_083500_create_admision_hospitalaria_tables.php`
* `database/seeders/AdmisionHospitalariaSeeder.php`
* `database/seeders/DatabaseSeeder.php`
* `app/Models/Paciente.php`
* `app/Models/Cama.php`
* `app/Models/Admision.php`
* `app/Http/Controllers/Api/V1/AdmisionHospitalariaController.php`
* `routes/api.php`
* `resources/js/modules/admisiones/pages/AdmisionesPage.vue`
* `resources/js/router/index.js`
* `resources/js/pages/HomePage.vue`

### Resultado del sprint

El módulo quedó funcional. Se logró registrar una admisión hospitalaria desde el frontend, guardar la información en PostgreSQL y actualizar el estado de la cama asignada.

### Commit asociado

`feat: implementar flujo base de admision hospitalaria`

---

## Sprint 3: Documentación, UML y evidencia final

### Objetivo del sprint

Documentar el módulo implementado, agregar diagramas UML y dejar evidencia verificable del trabajo realizado.

### Actividades realizadas

* Se documentó el módulo en el README.
* Se creó documentación específica del módulo.
* Se agregó la bitácora de integridad solicitada.
* Se prepararon diagramas UML:

  * Diagrama de caso de uso.
  * Diagrama de clases.
  * Diagrama de secuencia.
* Se dejó evidencia de funcionamiento del módulo.
* Se documentaron los commits principales por sprint.
* Se explicó cómo revisar la solución.

### Resultado del sprint

El proyecto quedó documentado y listo para revisión en GitHub y entrega en Canvas.

### Commit asociado

`docs: agregar UML y evidencia final del modulo de admision`

---

# Rutas principales del API

| Método | Ruta                                     | Descripción                              |
| ------ | ---------------------------------------- | ---------------------------------------- |
| GET    | `/api/v1/admisiones/catalogos`           | Consulta pacientes y camas disponibles   |
| GET    | `/api/v1/admisiones`                     | Lista admisiones registradas             |
| POST   | `/api/v1/admisiones`                     | Registra una nueva admisión hospitalaria |
| GET    | `/api/v1/admisiones/{admision}`          | Consulta una admisión específica         |
| POST   | `/api/v1/admisiones/{admision}/cancelar` | Cancela una admisión y libera la cama    |

---

# Ruta frontend

```txt
/admisiones
```

---

# Datos de prueba

### Tenant

```txt
00000000-0000-4000-8000-000000000001
```

### Usuario

```txt
jose@demo.com
```

### Contraseña

```txt
Password123
```

---

# Cómo revisar la solución

1. Clonar o abrir el fork personal.
2. Configurar el archivo `.env` con PostgreSQL.
3. Ejecutar las migraciones y seeders.
4. Compilar los assets con `npm run build`.
5. Levantar Laravel con `php artisan serve`.
6. Iniciar sesión con el tenant y usuario de prueba.
7. Entrar a `/admisiones`.
8. Registrar una admisión hospitalaria.
9. Verificar que la cama seleccionada cambie a ocupada.
10. Cancelar la admisión y verificar que la cama vuelva a quedar disponible.
