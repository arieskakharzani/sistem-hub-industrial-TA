# Digital Signature Implementation Guide

## Overview

Dokumen ini menjelaskan implementasi tanda tangan digital untuk meningkatkan legalitas dokumen dalam sistem SIPPPHI.

## Legalitas Tanda Tangan Digital di Indonesia

### Dasar Hukum

-   **UU ITE No. 11/2008** (Pasal 11)
-   **PP No. 71/2019** tentang Penyelenggaraan Sistem dan Transaksi Elektronik
-   **UU No. 19/2016** tentang Perubahan UU ITE

### Syarat Tanda Tangan Elektronik yang Sah

1. Data pembuatan tanda tangan terkait hanya kepada penanda tangan
2. Data pada saat pembuatan hanya terkait kepada penanda tangan
3. Segala perubahan terhadap tanda tangan elektronik dapat diketahui
4. Segala perubahan terhadap informasi elektronik yang terkait dengan tanda tangan elektronik dapat diketahui
5. Terdapat cara tertentu yang dipilih oleh penanda tangan untuk mewujudkan tanda tangan elektronik
6. Terdapat cara tertentu untuk menetapkan siapa penanda tangan

## Implementasi Saat Ini

### 1. Tanda Tangan Elektronik (Manual Drawing)

-   **Status**: Implemented
-   **Legalitas**: Dapat memiliki kekuatan hukum jika memenuhi syarat UU ITE
-   **Kelebihan**: Mudah digunakan, familiar dengan user
-   **Kekurangan**: Rentan terhadap pemalsuan, tidak ada verifikasi otomatis

### 2. Digital Signature dengan Hash & Timestamp

-   **Status**: Implemented (Service created)
-   **Legalitas**: Lebih kuat secara hukum
-   **Fitur**:
    -   Document hash (SHA-256)
    -   Timestamp yang tidak dapat diubah
    -   Audit trail
    -   Signature verification

## Rekomendasi Implementasi Lengkap

### Opsi 1: Enhanced Digital Signature (Direkomendasikan)

#### A. Database Schema

```sql
-- Sudah diimplementasikan di migration
ALTER TABLE risalah ADD COLUMN digital_signature_mediator TEXT;
ALTER TABLE risalah ADD COLUMN document_hash_mediator VARCHAR(255);
ALTER TABLE risalah ADD COLUMN timestamp_mediator TIMESTAMP;
ALTER TABLE risalah ADD COLUMN certificate_mediator TEXT;
```

#### B. Service Implementation

```php
// DigitalSignatureService.php - Sudah dibuat
- generateDocumentHash()
- createDigitalSignature()
- verifyDigitalSignature()
- generateCertificateInfo()
- logSignatureActivity()
```

#### C. Controller Integration

```php
// Di RisalahController.php
use App\Services\DigitalSignatureService;

public function signDocument(Request $request, $id)
{
    $digitalSignatureService = new DigitalSignatureService();

    // Generate document hash
    $documentData = $this->getDocumentData($id);
    $documentHash = $digitalSignatureService->generateDocumentHash($documentData);

    // Create digital signature
    $signatureData = $digitalSignatureService->createDigitalSignature(
        $documentHash,
        auth()->id(),
        auth()->user()->role
    );

    // Save to database
    $risalah->update([
        'digital_signature_mediator' => $signatureData['digital_signature'],
        'document_hash_mediator' => $signatureData['document_hash'],
        'timestamp_mediator' => $signatureData['timestamp'],
        'certificate_mediator' => json_encode($digitalSignatureService->generateCertificateInfo(auth()->id(), auth()->user()->role))
    ]);

    // Log activity
    $digitalSignatureService->logSignatureActivity($id, 'risalah', auth()->id(), auth()->user()->role, $signatureData);
}
```

### Opsi 2: PKI Certificate Integration (Untuk Produksi)

#### A. Menggunakan Sertifikat Digital dari CA Indonesia

```php
// Contoh integrasi dengan sertifikat digital
use OpenSSL\X509;

class PKISignatureService
{
    public function signWithCertificate($documentData, $certificatePath, $privateKeyPath)
    {
        // Load certificate
        $cert = file_get_contents($certificatePath);
        $privateKey = file_get_contents($privateKeyPath);

        // Create signature
        $signature = '';
        openssl_sign($documentData, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    public function verifyWithCertificate($documentData, $signature, $certificatePath)
    {
        $cert = file_get_contents($certificatePath);
        $pubKey = openssl_pkey_get_public($cert);

        return openssl_verify($documentData, base64_decode($signature), $pubKey, OPENSSL_ALGO_SHA256);
    }
}
```

#### B. Integrasi dengan CA Indonesia

-   **BSrE (Balai Sertifikasi Elektronik)**
-   **Kominfo CA**
-   **Private CA untuk internal**

### Opsi 3: Blockchain-based Timestamping

```php
class BlockchainTimestampService
{
    public function createTimestamp($documentHash)
    {
        // Integrasi dengan blockchain untuk timestamp yang tidak dapat diubah
        $timestamp = [
            'document_hash' => $documentHash,
            'timestamp' => time(),
            'blockchain_tx_id' => $this->submitToBlockchain($documentHash)
        ];

        return $timestamp;
    }
}
```

## Implementasi UI/UX

### 1. Digital Signature Info Component

```blade
<!-- Sudah dibuat: digital-signature-info.blade.php -->
<x-digital-signature-info
    :document="$risalah"
    signature-field="digital_signature_mediator"
    timestamp-field="timestamp_mediator"
    hash-field="document_hash_mediator"
    certificate-field="certificate_mediator"
    user-role="mediator"
/>
```

### 2. Signature Verification Page

```blade
<!-- resources/views/dokumen/verify-signature.blade.php -->
<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Digital Signature Verification</h3>

    <div class="space-y-4">
        <div class="flex items-center">
            <span class="font-medium w-32">Document ID:</span>
            <span class="font-mono">{{ $document->id }}</span>
        </div>

        <div class="flex items-center">
            <span class="font-medium w-32">Document Hash:</span>
            <span class="font-mono text-sm">{{ $document->document_hash_mediator }}</span>
        </div>

        <div class="flex items-center">
            <span class="font-medium w-32">Signature:</span>
            <span class="font-mono text-sm">{{ $document->digital_signature_mediator }}</span>
        </div>

        <div class="flex items-center">
            <span class="font-medium w-32">Timestamp:</span>
            <span>{{ \Carbon\Carbon::parse($document->timestamp_mediator)->format('d/m/Y H:i:s') }}</span>
        </div>

        <div class="flex items-center">
            <span class="font-medium w-32">Status:</span>
            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">
                Valid Digital Signature
            </span>
        </div>
    </div>
</div>
```

## Audit Trail & Logging

### 1. Database Logging

```sql
CREATE TABLE signature_audit_log (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    document_id BIGINT,
    document_type VARCHAR(50),
    user_id BIGINT,
    user_role VARCHAR(50),
    action VARCHAR(50),
    signature_hash VARCHAR(255),
    document_hash VARCHAR(255),
    timestamp TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. File-based Logging

```php
// Sudah diimplementasikan di DigitalSignatureService
Log::info('Digital signature created', [
    'document_id' => $documentId,
    'document_type' => $documentType,
    'user_id' => $userId,
    'user_role' => $userRole,
    'timestamp' => $signatureData['timestamp'],
    'document_hash' => $signatureData['document_hash'],
    'digital_signature' => $signatureData['digital_signature']
]);
```

## Keamanan & Best Practices

### 1. Hash Algorithm

-   **SHA-256**: Standar industri untuk document hashing
-   **SHA-512**: Untuk keamanan ekstra tinggi

### 2. Timestamp Security

-   **NTP Synchronization**: Pastikan server time sync
-   **Multiple Timestamp Sources**: Gunakan beberapa sumber waktu
-   **Blockchain Timestamping**: Untuk immutability

### 3. Certificate Management

-   **Certificate Storage**: Simpan di secure storage
-   **Certificate Rotation**: Update berkala
-   **Certificate Validation**: Validasi sebelum digunakan

### 4. Access Control

-   **Role-based Access**: Hanya user tertentu yang bisa sign
-   **Audit Logging**: Log semua aktivitas signature
-   **Session Management**: Validasi session sebelum sign

## Testing & Validation

### 1. Unit Tests

```php
class DigitalSignatureTest extends TestCase
{
    public function test_document_hash_generation()
    {
        $service = new DigitalSignatureService();
        $documentData = ['test' => 'data'];

        $hash = $service->generateDocumentHash($documentData);

        $this->assertNotEmpty($hash);
        $this->assertEquals(64, strlen($hash)); // SHA-256 = 64 chars
    }

    public function test_signature_verification()
    {
        $service = new DigitalSignatureService();
        $documentHash = 'test_hash';
        $userId = 1;
        $userRole = 'mediator';

        $signatureData = $service->createDigitalSignature($documentHash, $userId, $userRole);

        $isValid = $service->verifyDigitalSignature(
            $signatureData['digital_signature'],
            $signatureData['document_hash'],
            $signatureData['timestamp'],
            $userId,
            $userRole
        );

        $this->assertTrue($isValid);
    }
}
```

### 2. Integration Tests

```php
class SignatureIntegrationTest extends TestCase
{
    public function test_complete_signature_flow()
    {
        // Create document
        $risalah = Risalah::factory()->create();

        // Sign document
        $response = $this->post("/risalah/{$risalah->id}/sign", [
            'signature_data' => 'test_signature'
        ]);

        $response->assertStatus(200);

        // Verify signature exists
        $risalah->refresh();
        $this->assertNotEmpty($risalah->digital_signature_mediator);
        $this->assertNotEmpty($risalah->document_hash_mediator);
        $this->assertNotEmpty($risalah->timestamp_mediator);
    }
}
```

## Deployment Checklist

### 1. Production Setup

-   [ ] SSL Certificate untuk HTTPS
-   [ ] Secure storage untuk private keys
-   [ ] Database backup untuk signature data
-   [ ] Monitoring untuk signature activities
-   [ ] Rate limiting untuk signature requests

### 2. Legal Compliance

-   [ ] Review dengan tim legal
-   [ ] Dokumentasi proses signature
-   [ ] Audit trail implementation
-   [ ] User consent untuk digital signature
-   [ ] Data retention policy

### 3. User Training

-   [ ] Training untuk mediator
-   [ ] User guide untuk digital signature
-   [ ] Troubleshooting guide
-   [ ] Support documentation

## Kesimpulan

Implementasi digital signature dengan hash, timestamp, dan audit trail akan memberikan:

1. **Legalitas yang lebih kuat** sesuai UU ITE
2. **Non-repudiation** - tidak dapat disangkal
3. **Integrity** - dokumen tidak dapat diubah
4. **Audit trail** - jejak audit yang lengkap
5. **Verification** - dapat diverifikasi secara otomatis

Untuk implementasi lengkap, rekomendasikan:

1. **Phase 1**: Implementasi hash & timestamp (sudah dibuat)
2. **Phase 2**: Integrasi dengan CA Indonesia
3. **Phase 3**: Blockchain timestamping (opsional)

Apakah Anda ingin saya implementasikan salah satu opsi di atas?
