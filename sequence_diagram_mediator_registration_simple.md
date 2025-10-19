# Sequence Diagram - Use Case 1: Registrasi Mediator

## Sequence Diagram untuk Mediator Registration (Simple)

**Use Case:** Mediator mendaftarkan akun di sistem
**Aktor:** Mediator

```mermaid
sequenceDiagram
    participant M as Mediator
    participant UI as Registration Interface
    participant C as MediatorRegistrationController
    participant U as User Model
    participant MD as Mediator Model
    participant S as Storage

    M->>UI: Akses Form Registrasi
    UI->>C: GET showRegistrationForm()
    C->>UI: Return Registration Form
    UI->>M: Tampilkan Form

    M->>UI: Submit Form Data
    UI->>C: POST register(Request)
    C->>C: Validasi Input Data

    alt Validasi Gagal
        C->>UI: Return Validation Errors
        UI->>M: Tampilkan Error Message
    else Validasi Berhasil
        C->>U: Check Email Duplication
        U->>C: Return Duplication Status

        alt Email Duplikasi
            C->>UI: Return Error
            UI->>M: Tampilkan Error
        else Email Tidak Duplikasi
            C->>S: Upload SK File
            S->>C: Return File Path

            C->>U: Create User Account
            U->>C: User Created

            C->>MD: Create Mediator Record
            MD->>C: Mediator Created

            C->>UI: Redirect to Success Page
            UI->>M: Tampilkan Halaman Sukses
        end
    end
```

## Konversi ke PlantUML (Simple)

```plantuml
@startuml
!theme plain
skinparam backgroundColor #FFFFFF
skinparam sequence {
  ArrowColor #6C757D
  ActorBackgroundColor #E3F2FD
  LifeLineBackgroundColor #F8F9FA
  LifeLineBorderColor #6C757D
}

actor Mediator
participant "Registration Interface" as UI
participant "MediatorRegistrationController" as C
participant "User Model" as U
participant "Mediator Model" as MD
participant "Storage" as S

== Mediator Registration ==

Mediator -> UI: Akses Form Registrasi
UI -> C: GET showRegistrationForm()
C -> UI: Return Registration Form
UI -> Mediator: Tampilkan Form

Mediator -> UI: Submit Form Data
UI -> C: POST register(Request)
C -> C: Validasi Input Data

alt Validasi Gagal
    C -> UI: Return Validation Errors
    UI -> Mediator: Tampilkan Error Message
else Validasi Berhasil
    C -> U: Check Email Duplication
    U -> C: Return Duplication Status

    alt Email Duplikasi
        C -> UI: Return Error
        UI -> Mediator: Tampilkan Error
    else Email Tidak Duplikasi
        C -> S: Upload SK File
        S -> C: Return File Path

        C -> U: Create User Account
        U -> C: User Created

        C -> MD: Create Mediator Record
        MD -> C: Mediator Created

        C -> UI: Redirect to Success Page
        UI -> Mediator: Tampilkan Halaman Sukses
    end
end

@enduml
```
