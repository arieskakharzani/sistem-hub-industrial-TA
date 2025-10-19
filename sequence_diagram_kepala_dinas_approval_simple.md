# Sequence Diagram - Use Case 2: Kepala Dinas Approval

## Sequence Diagram untuk Kepala Dinas Approval (Simple)

**Use Case:** Kepala dinas menyetujui akun mediator
**Aktor:** Kepala Dinas

```mermaid
sequenceDiagram
    participant KD as Kepala Dinas
    participant UI as Approval Interface
    participant C as MediatorApprovalController
    participant MD as Mediator Model
    participant U as User Model
    participant E as Email Service

    KD->>UI: Login ke Dashboard
    UI->>C: GET index()
    C->>MD: Get Pending Mediators
    MD->>C: Return Pending List
    C->>UI: Return Approval Dashboard
    UI->>KD: Tampilkan Daftar Pending

    KD->>UI: Pilih Mediator untuk Review
    UI->>C: GET preview(id)
    C->>MD: Find Mediator by ID
    MD->>C: Return Mediator Data
    C->>UI: Return Preview Page
    UI->>KD: Tampilkan Detail Mediator

    KD->>UI: Download SK File
    UI->>C: GET downloadSk(id)
    C->>MD: Get File Path
    MD->>C: Return File Path
    C->>UI: Return File Download
    UI->>KD: Download SK File

    alt Kepala Dinas Memilih Approve
        KD->>UI: Klik Tombol Approve
        UI->>C: POST approve(id)
        C->>MD: Update Status to Approved
        MD->>C: Status Updated

        C->>U: Activate User Account
        U->>C: User Activated

        C->>E: Send Approval Email
        E->>C: Email Sent

        C->>UI: Redirect with Success Message
        UI->>KD: Tampilkan Success Message Approve

    else Kepala Dinas Memilih Reject
        KD->>UI: Klik Tombol Reject + Alasan
        UI->>C: POST reject(id, reason)
        C->>MD: Update Status to Rejected
        MD->>C: Status Updated

        C->>E: Send Rejection Email
        E->>C: Email Sent

        C->>UI: Redirect with Success Message
        UI->>KD: Tampilkan Success Message Reject
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

actor "Kepala Dinas" as KD
participant "Approval Interface" as UI
participant "MediatorApprovalController" as C
participant "Mediator Model" as MD
participant "User Model" as U
participant "Email Service" as E

== Kepala Dinas Approval ==

KD -> UI: Login ke Dashboard
UI -> C: GET index()
C -> MD: Get Pending Mediators
MD -> C: Return Pending List
C -> UI: Return Approval Dashboard
UI -> KD: Tampilkan Daftar Pending

KD -> UI: Pilih Mediator untuk Review
UI -> C: GET preview(id)
C -> MD: Find Mediator by ID
MD -> C: Return Mediator Data
C -> UI: Return Preview Page
UI -> KD: Tampilkan Detail Mediator

KD -> UI: Download SK File
UI -> C: GET downloadSk(id)
C -> MD: Get File Path
MD -> C: Return File Path
C -> UI: Return File Download
UI -> KD: Download SK File

alt Kepala Dinas Memilih Approve
    KD -> UI: Klik Tombol Approve
    UI -> C: POST approve(id)
    C -> MD: Update Status to Approved
    MD -> C: Status Updated

    C -> U: Activate User Account
    U -> C: User Activated

    C -> E: Send Approval Email
    E -> C: Email Sent

    C -> UI: Redirect with Success Message
    UI -> KD: Tampilkan Success Message Approve

else Kepala Dinas Memilih Reject
    KD -> UI: Klik Tombol Reject + Alasan
    UI -> C: POST reject(id, reason)
    C -> MD: Update Status to Rejected
    MD -> C: Status Updated

    C -> E: Send Rejection Email
    E -> C: Email Sent

    C -> UI: Redirect with Success Message
    UI -> KD: Tampilkan Success Message Reject
end

@enduml
```
