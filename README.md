## Entrega esperada

El estudiante debe trabajar sobre su propio fork del repositorio y entregar en Canvas el enlace al repositorio forkeado, junto con una breve descripción del módulo implementado y los commits principales que evidencian su avance.




## Módulo 8: Admisión hospitalaria

### Estudiante
José Alexánder Dionicio Córdova

### Descripción
Se implementó el flujo base de admisión hospitalaria. El módulo permite registrar el ingreso de un paciente, asociarlo con una cama disponible y actualizar automáticamente el estado de la cama a ocupada.

### Funcionalidades implementadas
- Listado de admisiones hospitalarias.
- Catálogo de pacientes.
- Catálogo de camas disponibles.
- Registro de admisión.
- Validación de paciente y cama por tenant.
- Cambio automático de cama disponible a ocupada.
- Cancelación de admisión y liberación de cama.
- Vista frontend en Vue para operar el módulo.

### Rutas principales del API
- GET /api/v1/admisiones/catalogos
- GET /api/v1/admisiones
- POST /api/v1/admisiones
- GET /api/v1/admisiones/{admision}
- POST /api/v1/admisiones/{admision}/cancelar

### Ruta frontend
- /admisiones

### Tenant de prueba
00000000-0000-4000-8000-000000000001

### Usuario de prueba
Correo: jose@demo.com  
Contraseña: Password123

### Commits principales sugeridos
- Sprint 1: docs: analizar arquitectura base y modulo de admision hospitalaria
- Sprint 2: feat: implementar flujo base de admision hospitalaria
- Sprint 3: docs: agregar UML y evidencia final del modulo de admision

### Cómo revisar la solución
1. Configurar el archivo .env con PostgreSQL.
2. Ejecutar las migraciones y seeders.
3. Levantar Laravel con php artisan serve.
4. Compilar los assets con npm run build o ejecutar Vite con npm run dev.
5. Iniciar sesión con el tenant y usuario de prueba.
6. Entrar al módulo /admisiones.
7. Registrar una admisión hospitalaria.
8. Verificar que la cama seleccionada cambie a ocupada.
9. Cancelar la admisión.
10. Verificar que la cama quede disponible nuevamente.
