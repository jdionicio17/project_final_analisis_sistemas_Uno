# Diagramas UML - Módulo 8: Admisión hospitalaria

## 1. Diagrama de caso de uso

```mermaid
flowchart LR
    Recepcionista((Recepcionista))

    UC1[Iniciar sesión]
    UC2[Consultar pacientes]
    UC3[Consultar camas disponibles]
    UC4[Registrar admisión hospitalaria]
    UC5[Asignar cama al paciente]
    UC6[Consultar admisiones registradas]
    UC7[Cancelar admisión]
    UC8[Liberar cama]

    Recepcionista --> UC1
    Recepcionista --> UC2
    Recepcionista --> UC3
    Recepcionista --> UC4
    UC4 --> UC5
    Recepcionista --> UC6
    Recepcionista --> UC7
    UC7 --> UC8
```

---

## 2. Diagrama de clases

Este diagrama representa las clases principales implementadas en el módulo de admisión hospitalaria. A diferencia de un diagrama entidad-relación, aquí se incluyen atributos y métodos propios de las clases.

```mermaid
classDiagram

    class Paciente {
        +int id
        +string tenant_id
        +string nombres
        +string apellidos
        +string documento
        +date fecha_nacimiento
        +string sexo
        +string telefono
        +string direccion
        +array fillable
        +array casts
        +admisiones() HasMany
        +getNombreCompletoAttribute() string
    }

    class Cama {
        +int id
        +string tenant_id
        +string codigo
        +string area
        +string ubicacion
        +string estado
        +array fillable
        +const ESTADO_DISPONIBLE
        +const ESTADO_OCUPADA
        +const ESTADO_MANTENIMIENTO
        +admisiones() HasMany
    }

    class Admision {
        +int id
        +string tenant_id
        +int paciente_id
        +int cama_id
        +int usuario_id
        +datetime fecha_admision
        +string motivo
        +string diagnostico_ingreso
        +string estado
        +string observaciones
        +array fillable
        +array casts
        +const ESTADO_ACTIVA
        +const ESTADO_CANCELADA
        +paciente() BelongsTo
        +cama() BelongsTo
        +usuario() BelongsTo
    }

    class User {
        +int id
        +string tenant_id
        +string name
        +string email
        +string password
        +array fillable
        +array hidden
        +tenant() BelongsTo
        +getJWTIdentifier() mixed
        +getJWTCustomClaims() array
    }

    class AdmisionHospitalariaController {
        +catalogos(Request request) JsonResponse
        +index(Request request) JsonResponse
        +store(Request request) JsonResponse
        +show(Request request, Admision admision) JsonResponse
        +cancelar(Request request, Admision admision) JsonResponse
        -validarTenant(Request request, Admision admision) void
        -tenantId(Request request) string
    }

    Paciente "1" --> "0..*" Admision : tiene
    Cama "1" --> "0..*" Admision : es asignada en
    User "1" --> "0..*" Admision : registra
    AdmisionHospitalariaController --> Paciente : consulta
    AdmisionHospitalariaController --> Cama : consulta/actualiza
    AdmisionHospitalariaController --> Admision : crea/lista/cancela
```

---

## 3. Diagrama de secuencia

```mermaid
sequenceDiagram
    actor Recepcionista
    participant Vue as Vista Vue Admisiones
    participant API as AdmisionHospitalariaController
    participant Paciente as Modelo Paciente
    participant Cama as Modelo Cama
    participant Admision as Modelo Admision
    participant DB as PostgreSQL

    Recepcionista->>Vue: Completa formulario de admisión
    Vue->>API: POST /api/v1/admisiones
    API->>Paciente: Verificar paciente por tenant
    Paciente->>DB: SELECT paciente
    DB-->>Paciente: Paciente encontrado
    API->>Cama: Verificar cama disponible
    Cama->>DB: SELECT cama FOR UPDATE
    DB-->>Cama: Cama disponible
    API->>Admision: Crear admisión
    Admision->>DB: INSERT admisiones
    API->>Cama: Cambiar estado a ocupada
    Cama->>DB: UPDATE camas
    API-->>Vue: Respuesta exitosa
    Vue-->>Recepcionista: Muestra admisión registrada
```

---

## 4. Diagrama de secuencia para cancelar admisión

```mermaid
sequenceDiagram
    actor Recepcionista
    participant Vue as Vista Vue Admisiones
    participant API as AdmisionHospitalariaController
    participant Admision as Modelo Admision
    participant Cama as Modelo Cama
    participant DB as PostgreSQL

    Recepcionista->>Vue: Presiona cancelar admisión
    Vue->>API: POST /api/v1/admisiones/{id}/cancelar
    API->>Admision: Verificar admisión activa
    Admision->>DB: SELECT admisión
    DB-->>Admision: Admisión encontrada
    API->>Admision: Cambiar estado a cancelada
    Admision->>DB: UPDATE admisiones
    API->>Cama: Liberar cama asociada
    Cama->>DB: UPDATE camas SET estado = disponible
    API-->>Vue: Respuesta exitosa
    Vue-->>Recepcionista: Muestra admisión cancelada y cama liberada
```
