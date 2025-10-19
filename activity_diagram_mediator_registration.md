# Activity Diagram - Use Case 1: Registrasi Mediator

## Activity Diagram untuk Mediator Registration

**Use Case:** Mediator mendaftarkan akun di sistem
**Aktor:** Mediator

```mermaid
flowchart TD
    subgraph Actor["Mediator"]
        A[Mediator Mengakses Form Registrasi]
        B[Mengisi Data Registrasi]
        C[Nama Lengkap]
        D[NIP]
        E[Email]
        F[Upload File SK]
        G[Lihat Error Message]
        H[Lihat Error Duplikasi]
        I[Lihat Halaman Sukses]
        J[Terima Email Kredensial]
        K[Login ke Sistem]
        L[Registrasi Ulang]
    end

    subgraph System["Sistem"]
        M[Validasi Input Data]
        N{Cek Duplikasi NIP/Email}
        O[Generate Password Random]
        P[Buat User Account]
        Q[Buat Mediator Record]
        R[Upload File SK ke Storage]
        S[Set Status: Pending]
        T[Kirim Notifikasi ke Kepala Dinas]
        U[Kirim Email Kredensial Login]
        V[Kirim Email Penolakan]
    end

    %% Main Flow
    A --> B
    B --> C
    B --> D
    B --> E
    B --> F
    C --> M
    D --> M
    E --> M
    F --> M

    M --> N
    N -->|Duplikasi| G
    N -->|Tidak Duplikasi| O
    G --> B
    H --> B

    O --> P
    P --> Q
    Q --> R
    R --> S
    S --> T

    %% Fork untuk paralel flow
    T --> I
    T --> J

    %% Response Flow
    J --> K
    J --> L
    L --> B

    %% Styling - Pool tanpa warna, Activity biru
    classDef actorPool fill:#FFFFFF,stroke:#000000,stroke-width:2px
    classDef systemPool fill:#FFFFFF,stroke:#000000,stroke-width:2px
    classDef activity fill:#E3F2FD,stroke:#1976D2,stroke-width:2px,color:#000000
    classDef decision fill:#FFF3E0,stroke:#E65100,stroke-width:2px,color:#000000

    class Actor actorPool
    class System systemPool
    class A,B,C,D,E,F,G,H,I,J,K,L,M,O,P,Q,R,S,T,U,V activity
    class N decision
```

## Konversi ke PlantUML

```plantuml
@startuml MediatorRegistrationActivity
!theme plain
skinparam backgroundColor #FFFFFF
skinparam activity {
  BackgroundColor #E3F2FD
  BorderColor #1976D2
  FontColor #000000
}
skinparam activityDiamond {
  BackgroundColor #FFF3E0
  BorderColor #E65100
}
skinparam swimlane {
  BackgroundColor #FFFFFF
  BorderColor #000000
}

|#FFFFFF| Mediator |
start
:Mediator Mengakses Form Registrasi;
:Mengisi Data Registrasi;
:Nama Lengkap;
:NIP;
:Email;
:Upload File SK;

|#FFFFFF| Sistem |
:Validasi Input Data;
if (Cek Duplikasi NIP/Email) then (Duplikasi)
  |#FFFFFF| Mediator |
  :Lihat Error Message;
  |#FFFFFF| Sistem |
else (Tidak Duplikasi)
  :Generate Password Random;
  :Buat User Account;
  :Buat Mediator Record;
  :Upload File SK ke Storage;
  :Set Status: Pending;
  :Kirim Notifikasi ke Kepala Dinas;
endif

fork
  |#FFFFFF| Mediator |
  :Lihat Halaman Sukses;
fork again
  |#FFFFFF| Mediator |
  :Terima Email Kredensial;
  :Login ke Sistem;
end fork

@enduml
```
