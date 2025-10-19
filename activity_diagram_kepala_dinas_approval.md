# Activity Diagram - Use Case 2: Kepala Dinas Approval

## Activity Diagram untuk Kepala Dinas Approval

**Use Case:** Kepala dinas menyetujui akun mediator
**Aktor:** Kepala Dinas

```mermaid
flowchart TD
    subgraph Actor["Kepala Dinas"]
        A[Kepala Dinas Login]
        B[Akses Dashboard Approval]
        C[Lihat Daftar Pending Mediators]
        D[Pilih Mediator]
        E[Preview Detail Mediator]
        F[Download File SK]
        G{Keputusan Approval}
        H[Lihat Success Message Approve]
        I[Lihat Success Message Reject]
    end

    subgraph System["Sistem"]
        J[Get Pending Mediators List]
        K[Find Mediator by ID]
        L[Download File from Storage]
        M[Update Status: Approved]
        N[Set Approved By & Date]
        O[Aktifkan User Account]
        P[Set Email Verified]
        Q[Generate Password Baru]
        R[Kirim Email Kredensial Login]
        S[Update Status: Rejected]
        T[Set Rejection Reason]
        U[Set Rejection Date]
        V[Kirim Email Penolakan]
    end

    %% Main Flow
    A --> B
    B --> C
    C --> J
    J --> D
    D --> E
    E --> K
    K --> F
    F --> L
    L --> G

    %% Approval Flow
    G -->|Approve| M
    M --> N
    N --> O
    O --> P
    P --> Q
    Q --> R
    R --> H

    %% Rejection Flow
    G -->|Reject| S
    S --> T
    T --> U
    U --> V
    V --> I

    %% Styling - Pool tanpa warna, Activity biru
    classDef actorPool fill:#FFFFFF,stroke:#000000,stroke-width:2px
    classDef systemPool fill:#FFFFFF,stroke:#000000,stroke-width:2px
    classDef activity fill:#E3F2FD,stroke:#1976D2,stroke-width:2px,color:#000000
    classDef decision fill:#FFF3E0,stroke:#E65100,stroke-width:2px,color:#000000

    class Actor actorPool
    class System systemPool
    class A,B,C,D,E,F,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V activity
    class G decision
```

## Konversi ke PlantUML (Format Sederhana)

```plantuml
@startuml
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

|#FFFFFF| Kepala Dinas |
start
:Kepala Dinas Login;
:Akses Dashboard Approval;
:Lihat Daftar Pending Mediators;
:Pilih Mediator;
:Preview Detail Mediator;
:Download File SK;

|#FFFFFF| Sistem |
if (Keputusan Approval) then (Approve)
  :Update Status: Approved;
  :Aktifkan User Account;
  :Kirim Email Kredensial Login;
  |#FFFFFF| Kepala Dinas |
  :Lihat Success Message Approve;
stop
else (Reject)
  :Update Status: Rejected;
  :Kirim Email Penolakan;
  |#FFFFFF| Kepala Dinas |
  :Lihat Success Message Reject;
stop
endif

@enduml
```
