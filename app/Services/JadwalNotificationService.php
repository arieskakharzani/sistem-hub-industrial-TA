<?php
// app/Services/JadwalNotificationService.php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Terlapor;
use Illuminate\Support\Facades\Log;
use App\Notifications\JadwalNotification;

class JadwalNotificationService
{
    /**
     * Get recipients for jadwal mediation notification
     * 
     * @param Jadwal $jadwal
     * @return array
     */
    public function getRecipients(Jadwal $jadwal): array
    {
        $recipients = [];

        try {
            // Load necessary relationships dengan eager loading yang lengkap
            $jadwal->load([
                'pengaduan.pelapor.user',
                'pengaduan.terlapor',
                'mediator'
            ]);

            Log::info('🔍 Getting recipients for jadwal', [
                'jadwal_id' => $jadwal->jadwal_id,
                'pengaduan_id' => $jadwal->pengaduan_id,
                'nama_terlapor' => $jadwal->pengaduan->nama_terlapor ?? 'not found',
                'terlapor_id' => $jadwal->pengaduan->terlapor_id ?? 'not found',
                'email_terlapor_field' => $jadwal->pengaduan->email_terlapor ?? 'not found'
            ]);

            // Add Pelapor as recipient
            $pelapor = $jadwal->pengaduan->pelapor ?? null;
            if ($pelapor && $pelapor->email) {
                $recipients[] = [
                    'name' => $pelapor->nama_pelapor,
                    'email' => $pelapor->email,
                    'role' => 'pelapor',
                    'user' => $pelapor->user ?? null,
                    'type' => 'Pelapor'
                ];

                Log::info('✅ Pelapor recipient added', [
                    'name' => $pelapor->nama_pelapor,
                    'email' => $pelapor->email
                ]);
            } else {
                Log::warning('❌ Pelapor data incomplete', [
                    'pelapor_exists' => $pelapor ? 'yes' : 'no',
                    'email_exists' => $pelapor?->email ? 'yes' : 'no'
                ]);
            }

            // Add Terlapor as recipient - dengan multiple fallback methods
            $terlaporData = $this->getTerlaporData($jadwal);
            if ($terlaporData) {
                $recipients[] = [
                    'name' => $terlaporData['name'],
                    'email' => $terlaporData['email'],
                    'role' => 'terlapor',
                    'user' => null,
                    'type' => 'Terlapor'
                ];

                Log::info('✅ Terlapor recipient added', [
                    'name' => $terlaporData['name'],
                    'email' => $terlaporData['email']
                ]);
            } else {
                Log::warning('❌ Terlapor email not found', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'nama_terlapor' => $jadwal->pengaduan->nama_terlapor ?? 'not found'
                ]);
            }

            Log::info('📊 Total recipients found', [
                'jadwal_id' => $jadwal->jadwal_id,
                'recipients_count' => count($recipients),
                'recipients' => array_map(function ($r) {
                    return $r['role'] . ': ' . $r['email'];
                }, $recipients)
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error getting recipients for jadwal notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $recipients;
    }

    /**
     * Get terlapor data with multiple fallback methods
     * 
     * @param Jadwal $jadwal
     * @return array|null
     */
    private function getTerlaporData(Jadwal $jadwal): ?array
    {
        try {
            Log::info('🔍 Starting terlapor data search', [
                'jadwal_id' => $jadwal->jadwal_id,
                'terlapor_id' => $jadwal->pengaduan->terlapor_id ?? 'null',
                'email_terlapor_field' => $jadwal->pengaduan->email_terlapor ?? 'null',
                'nama_terlapor' => $jadwal->pengaduan->nama_terlapor ?? 'null'
            ]);

            // Method 1: Cek relationship terlapor langsung (paling optimal)
            if ($jadwal->pengaduan->terlapor && $jadwal->pengaduan->terlapor->email_terlapor) {
                Log::info('✅ Method 1 SUCCESS: Found via relationship', [
                    'terlapor_id' => $jadwal->pengaduan->terlapor->terlapor_id,
                    'email' => $jadwal->pengaduan->terlapor->email_terlapor
                ]);

                return [
                    'name' => $jadwal->pengaduan->terlapor->nama_terlapor,
                    'email' => $jadwal->pengaduan->terlapor->email_terlapor
                ];
            }

            // Method 2: Cek field email_terlapor langsung di pengaduan
            if (!empty($jadwal->pengaduan->email_terlapor)) {
                Log::info('✅ Method 2 SUCCESS: Found via direct email field', [
                    'email_terlapor' => $jadwal->pengaduan->email_terlapor
                ]);

                return [
                    'name' => $jadwal->pengaduan->nama_terlapor ?? 'Terlapor',
                    'email' => $jadwal->pengaduan->email_terlapor
                ];
            }

            // Method 3: Cek apakah ada terlapor_id dan query langsung
            if (!empty($jadwal->pengaduan->terlapor_id)) {
                Log::info('🔍 Method 3: Trying by terlapor_id', [
                    'terlapor_id' => $jadwal->pengaduan->terlapor_id
                ]);

                $terlapor = Terlapor::find($jadwal->pengaduan->terlapor_id);
                if ($terlapor && !empty($terlapor->email_terlapor)) {
                    Log::info('✅ Method 3 SUCCESS: Found by terlapor_id', [
                        'email' => $terlapor->email_terlapor
                    ]);

                    return [
                        'name' => $terlapor->nama_terlapor,
                        'email' => $terlapor->email_terlapor
                    ];
                }
            }

            // Method 4: Cari berdasarkan nama_terlapor (exact match)
            if (!empty($jadwal->pengaduan->nama_terlapor)) {
                Log::info('🔍 Method 4: Trying by nama_terlapor exact match', [
                    'nama_terlapor' => $jadwal->pengaduan->nama_terlapor
                ]);

                $terlapor = Terlapor::where('nama_terlapor', $jadwal->pengaduan->nama_terlapor)
                    ->whereNotNull('email_terlapor')
                    ->where('email_terlapor', '!=', '')
                    ->first();

                if ($terlapor) {
                    Log::info('✅ Method 4 SUCCESS: Found by nama exact match', [
                        'email' => $terlapor->email_terlapor
                    ]);

                    return [
                        'name' => $terlapor->nama_terlapor,
                        'email' => $terlapor->email_terlapor
                    ];
                }
            }

            // Method 5: Cari berdasarkan nama dengan LIKE (case insensitive)
            if (!empty($jadwal->pengaduan->nama_terlapor)) {
                Log::info('🔍 Method 5: Trying by nama_terlapor LIKE match', [
                    'nama_terlapor' => $jadwal->pengaduan->nama_terlapor
                ]);

                $terlapor = Terlapor::where('nama_terlapor', 'LIKE', '%' . $jadwal->pengaduan->nama_terlapor . '%')
                    ->whereNotNull('email_terlapor')
                    ->where('email_terlapor', '!=', '')
                    ->first();

                if ($terlapor) {
                    Log::info('✅ Method 5 SUCCESS: Found by nama LIKE match', [
                        'email' => $terlapor->email_terlapor
                    ]);

                    return [
                        'name' => $terlapor->nama_terlapor,
                        'email' => $terlapor->email_terlapor
                    ];
                }
            }

            // Method 6: Log semua terlapor yang ada untuk debugging
            $allTerlapor = Terlapor::select('terlapor_id', 'nama_terlapor', 'email_terlapor')
                ->whereNotNull('email_terlapor')
                ->where('email_terlapor', '!=', '')
                ->get();

            Log::warning('❌ No terlapor found - All available terlapor with emails', [
                'count' => $allTerlapor->count(),
                'terlapor_list' => $allTerlapor->map(function ($t) {
                    return [
                        'id' => $t->terlapor_id,
                        'name' => $t->nama_terlapor,
                        'email' => $t->email_terlapor
                    ];
                })->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error in getTerlaporData', [
                'jadwal_id' => $jadwal->jadwal_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }

    /**
     * Get mediator for konfirmasi notifications
     * DIPERLUKAN untuk SendKonfirmasiNotification
     */
    public function getMediator(Jadwal $jadwal): ?array
    {
        try {
            $jadwal->load(['mediator.user']);

            $mediator = $jadwal->mediator;

            if (!$mediator || !$mediator->user || !$mediator->user->email) {
                Log::warning('❌ Mediator not found or has no email for jadwal', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'mediator_id' => $jadwal->mediator_id
                ]);
                return null;
            }

            Log::info('✅ Mediator found for notification', [
                'mediator_id' => $mediator->mediator_id,
                'mediator_name' => $mediator->nama_mediator,
                'email' => $mediator->user->email
            ]);

            return [
                'email' => $mediator->user->email,
                'name' => $mediator->nama_mediator,
                'role' => 'mediator',
                'user' => $mediator->user,
                'profile' => $mediator
            ];
        } catch (\Exception $e) {
            Log::error('❌ Error getting mediator for notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if notification should include in-app notification
     * DIPERLUKAN untuk conditional notification channels
     */
    public function shouldSendInAppNotification(string $userRole): bool
    {
        // Only mediator gets in-app notifications
        return $userRole === 'mediator';
    }

    /**
     * Get notification channels based on user role
     * DIPERLUKAN untuk determine channels
     */
    public function getNotificationChannels(string $userRole): array
    {
        if ($userRole === 'mediator') {
            return ['mail', 'database']; // Email + In-app
        }

        return ['mail']; // Only email for pelapor/terlapor
    }

    /**
     * Format notification data for email template
     * 
     * @param Jadwal $jadwal
     * @param string $eventType
     * @param array $additionalData
     * @return array
     */
    public function formatNotificationData(Jadwal $jadwal, string $eventType, array $additionalData = []): array
    {
        $data = [
            'jadwal' => [
                'id' => $jadwal->jadwal_id,
                'tanggal' => $jadwal->tanggal->format('d F Y'),
                'waktu' => $jadwal->waktu->format('H:i'),
                'tempat' => $jadwal->tempat,
                'status' => $jadwal->status_jadwal,
                'status_label' => $this->getStatusLabel($jadwal->status_jadwal),
                'catatan' => $jadwal->catatan_jadwal,
            ],
            'pengaduan' => [
                'id' => $jadwal->pengaduan->pengaduan_id,
                'perihal' => $jadwal->pengaduan->perihal,
                'nama_terlapor' => $jadwal->pengaduan->nama_terlapor,
                'tanggal_laporan' => $jadwal->pengaduan->tanggal_laporan->format('d F Y'),
            ],
            'mediator' => [
                'nama' => $jadwal->mediator->nama_mediator ?? 'Mediator',
                'nip' => $jadwal->mediator->nip ?? '',
            ],
            'event_type' => $eventType,
            'event_label' => $this->getEventLabel($eventType),
        ];

        // Add event-specific data
        if ($eventType === 'status_updated' && isset($additionalData['old_status'])) {
            $data['old_status'] = $additionalData['old_status'];
            $data['old_status_label'] = $this->getStatusLabel($additionalData['old_status']);
        }

        if ($eventType === 'updated' && isset($additionalData['old_data'])) {
            $data['changes'] = $this->getChanges($jadwal, $additionalData['old_data']);
        }

        return $data;
    }

    /**
     * Get status label in Indonesian
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'dijadwalkan' => 'Dijadwalkan',
            'berlangsung' => 'Sedang Berlangsung',
            'selesai' => 'Selesai',
            'ditunda' => 'Ditunda',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($status)
        };
    }

    /**
     * Get event label in Indonesian
     */
    private function getEventLabel(string $eventType): string
    {
        return match ($eventType) {
            'created' => 'Jadwal Baru',
            'updated' => 'Perubahan Jadwal',
            'status_updated' => 'Update Status Jadwal',
            default => 'Notifikasi Jadwal'
        };
    }

    /**
     * Get changes between old and new data
     */
    private function getChanges(Jadwal $jadwal, array $oldData): array
    {
        $changes = [];

        $fields = [
            'tanggal' => 'Tanggal',
            'waktu' => 'Waktu',
            'tempat' => 'Tempat',
            'catatan_jadwal' => 'Catatan'
        ];

        foreach ($fields as $field => $label) {
            if (isset($oldData[$field]) && $oldData[$field] !== $jadwal->$field) {
                $changes[] = [
                    'field' => $label,
                    'old' => $oldData[$field],
                    'new' => $jadwal->$field
                ];
            }
        }

        return $changes;
    }

    /**
     * Debug method - get all terlapor for troubleshooting
     */
    public function debugTerlaporData(): array
    {
        return Terlapor::select('terlapor_id', 'nama_terlapor', 'email_terlapor', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }
}
