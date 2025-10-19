<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';
    protected $primaryKey = 'pengaduan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pengaduan_id',
        'pelapor_id',
        'terlapor_id', // Updated to match terlapor table
        'mediator_id',
        'tanggal_laporan',
        'perihal',
        'masa_kerja',
        'nama_terlapor', // Updated to match terlapor table
        'email_terlapor', // Updated to match terlapor table
        'no_hp_terlapor', // Updated to match terlapor table
        'alamat_kantor_cabang',
        'narasi_kasus',
        'catatan_tambahan',
        'risalah_bipartit',
        'lampiran',
        'status',
        'catatan_mediator',
        'assigned_at'
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'lampiran' => 'array',
        'assigned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate UUID saat membuat pengaduan baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->pengaduan_id)) {
                $model->pengaduan_id = (string) Str::uuid();
            }
            // Generate nomor_pengaduan otomatis
            if (empty($model->nomor_pengaduan)) {
                $year = now()->year;
                $last = self::whereYear('created_at', $year)
                    ->whereNotNull('nomor_pengaduan')
                    ->orderByDesc('nomor_pengaduan')
                    ->first();
                $next = 1;
                if ($last && preg_match('/PGD-' . $year . '-(\\d{4})$/', $last->nomor_pengaduan, $matches)) {
                    $next = intval($matches[1]) + 1;
                }
                $model->nomor_pengaduan = sprintf('PGD-%d-%04d', $year, $next);
            }
        });
    }


    /**
     * Get the pelapor that owns the pengaduan.
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pelapor::class, 'pelapor_id', 'pelapor_id');
    }

    /**
     * Get the terlapor that owns the pengaduan.
     */
    public function terlapor(): BelongsTo
    {
        return $this->belongsTo(Terlapor::class, 'terlapor_id', 'terlapor_id');
    }

    public function mediator(): BelongsTo
    {
        return $this->belongsTo(Mediator::class, 'mediator_id', 'mediator_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'pengaduan_id', 'pengaduan_id');
    }

    public function hasActiveJadwal(): bool
    {
        return $this->jadwal()
            ->whereIn('status_jadwal', ['dijadwalkan', 'berlangsung'])
            ->exists();
    }

    public function dokumenHI()
    {
        return $this->hasMany(DokumenHubunganIndustrial::class, 'pengaduan_id', 'pengaduan_id');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pengaduan hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_laporan', today());
    }

    /**
     * Scope untuk filter berdasarkan perihal
     */
    public function scopeByPerihal($query, $perihal)
    {
        return $query->where('perihal', $perihal);
    }

    /**
     * Scope untuk pengaduan yang belum di-assign
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('mediator_id');
    }

    /**
     * Scope untuk pengaduan yang sudah di-assign ke mediator tertentu
     */
    public function scopeAssignedTo($query, $mediatorId)
    {
        return $query->where('mediator_id', $mediatorId);
    }

    /**
     * Check if pengaduan is assigned to specific mediator
     */
    public function isAssignedTo($mediatorId): bool
    {
        return $this->mediator_id === $mediatorId;
    }

    /**
     * Check if pengaduan is unassigned
     */
    public function isUnassigned(): bool
    {
        return is_null($this->mediator_id);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'proses' => 'bg-blue-100 text-blue-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted status text
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Review',
            'proses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            default => 'Status Tidak Dikenal'
        };
    }

    /**
     * Get formatted perihal text
     */
    public function getPerihalTextAttribute()
    {
        return $this->perihal;
    }

    /**
     * Get risalah bipartit file URL
     */
    public function getRisalahBipartitUrlAttribute()
    {
        return $this->risalah_bipartit ? asset('storage/' . $this->risalah_bipartit) : null;
    }

    /**
     * Check if risalah bipartit exists
     */
    public function hasRisalahBipartit(): bool
    {
        return !empty($this->risalah_bipartit) && file_exists(storage_path('app/public/' . $this->risalah_bipartit));
    }

    /**
     * Get lampiran files as array
     */
    public function getLampiranFilesAttribute()
    {
        return $this->lampiran ?? [];
    }

    /**
     * Constants untuk enum values
     */
    public static function getPerihalOptions()
    {
        return [
            'Perselisihan Hak',
            'Perselisihan Kepentingan',
            'Perselisihan PHK',
            'Perselisihan antar SP/SB'
        ];
    }

    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai'
        ];
    }

    /**
     * Check if this pengaduan has any active mediasi schedule
     */
    public function hasActiveMediasiSchedule()
    {
        return $this->jadwal()
            ->where('jenis_jadwal', 'mediasi')
            ->whereNotIn('status_jadwal', ['selesai', 'dibatalkan'])
            ->exists();
    }

    /**
     * Get the latest mediasi schedule for this pengaduan
     */
    public function getLatestMediasiSchedule()
    {
        return $this->jadwal()
            ->where('jenis_jadwal', 'mediasi')
            ->latest('created_at')
            ->first();
    }

    /**
     * Get the count of completed mediasi sessions
     */
    public function getCompletedMediasiSessionsCount()
    {
        return $this->jadwal()
            ->where('jenis_jadwal', 'mediasi')
            ->where('status_jadwal', 'selesai')
            ->count();
    }

    /**
     * Check if this pengaduan has reached maximum mediasi sessions (3)
     */
    public function hasReachedMaxMediasiSessions()
    {
        return $this->getCompletedMediasiSessionsCount() >= 3;
    }

    /**
     * Check if next mediasi schedule already exists for given sidang_ke
     */
    public function hasNextMediasiSchedule($currentSidangKe)
    {
        return $this->jadwal()
            ->where('jenis_jadwal', 'mediasi')
            ->where('sidang_ke', $currentSidangKe + 1)
            ->exists();
    }

    /**
     * Get next mediasi schedule if exists
     */
    public function getNextMediasiSchedule($currentSidangKe)
    {
        return $this->jadwal()
            ->where('jenis_jadwal', 'mediasi')
            ->where('sidang_ke', $currentSidangKe + 1)
            ->first();
    }

    /**
     * Get all mediasi sessions for this pengaduan
     */
    public function getMediasiSessions()
    {
        return $this->jadwal()
            ->where('jenis_jadwal', 'mediasi')
            ->orderBy('sidang_ke', 'asc')
            ->get();
    }

    /**
     * Check if pelapor is unresponsive (always absent or no confirmation in all mediasi sessions)
     */
    public function isPelaporUnresponsive()
    {
        $mediasiSessions = $this->getMediasiSessions();

        if ($mediasiSessions->count() < 3) {
            return false; // Belum mencapai 3 sesi
        }

        // Cek apakah pelapor selalu tidak hadir atau tidak konfirmasi di semua sesi
        foreach ($mediasiSessions as $session) {
            if ($session->konfirmasi_pelapor === 'hadir') {
                return false; // Ada sesi dimana pelapor hadir
            }
        }

        return true; // Pelapor tidak pernah hadir di semua sesi
    }

    /**
     * Check if terlapor is unresponsive (always absent or no confirmation in all mediasi sessions)
     */
    public function isTerlaporUnresponsive()
    {
        $mediasiSessions = $this->getMediasiSessions();

        if ($mediasiSessions->count() < 3) {
            return false; // Belum mencapai 3 sesi
        }

        // Cek apakah terlapor selalu tidak hadir atau tidak konfirmasi di semua sesi
        foreach ($mediasiSessions as $session) {
            if ($session->konfirmasi_terlapor === 'hadir') {
                return false; // Ada sesi dimana terlapor hadir
            }
        }

        return true; // Terlapor tidak pernah hadir di semua sesi
    }

    /**
     * Check if pengaduan should be auto-completed due to pelapor unresponsiveness
     */
    public function shouldAutoCompleteDueToPelaporUnresponsiveness()
    {
        return $this->hasReachedMaxMediasiSessions() && $this->isPelaporUnresponsive();
    }

    /**
     * Check if mediator can create anjuran due to terlapor unresponsiveness
     */
    public function canCreateAnjuranDueToTerlaporUnresponsiveness()
    {
        return $this->hasReachedMaxMediasiSessions() && $this->isTerlaporUnresponsive();
    }

    /**
     * Check if mediator can create anjuran due to mixed attendance failure
     * (when both parties are not unresponsive but no mediasi risalah was created)
     */
    public function canCreateAnjuranDueToMixedAttendanceFailure()
    {
        return $this->hasReachedMaxMediasiSessions() &&
            !$this->isPelaporUnresponsive() &&
            !$this->isTerlaporUnresponsive() &&
            !$this->hasAnyMediasiRisalah();
    }

    /**
     * Check if this pengaduan has any mediasi risalah
     */
    public function hasAnyMediasiRisalah()
    {
        return $this->dokumenHI()
            ->whereHas('risalah', function ($query) {
                $query->where('jenis_risalah', 'mediasi');
            })
            ->exists();
    }

    /**
     * Check if mediator can create anjuran (combined logic)
     */
    public function canCreateAnjuran()
    {
        return $this->canCreateAnjuranDueToTerlaporUnresponsiveness() ||
            $this->canCreateAnjuranDueToMixedAttendanceFailure();
    }

    /**
     * Check if anjuran already exists for this pengaduan
     */
    public function hasAnjuran()
    {
        return $this->dokumenHI()
            ->whereHas('anjuran')
            ->exists();
    }

    /**
     * Get existing anjuran for this pengaduan
     */
    public function getAnjuran()
    {
        $dokumenHI = $this->dokumenHI()->first();
        if (!$dokumenHI) {
            return null;
        }

        return $dokumenHI->anjuran()->first();
    }

    /**
     * Get responsiveness summary for both parties
     */
    public function getResponsivenessSummary()
    {
        $mediasiSessions = $this->getMediasiSessions();

        $summary = [
            'total_sessions' => $mediasiSessions->count(),
            'pelapor' => [
                'hadir' => 0,
                'tidak_hadir' => 0,
                'pending' => 0,
                'unresponsive' => false
            ],
            'terlapor' => [
                'hadir' => 0,
                'tidak_hadir' => 0,
                'pending' => 0,
                'unresponsive' => false
            ]
        ];

        foreach ($mediasiSessions as $session) {
            // Count pelapor responses
            if ($session->konfirmasi_pelapor === 'hadir') {
                $summary['pelapor']['hadir']++;
            } elseif ($session->konfirmasi_pelapor === 'tidak_hadir') {
                $summary['pelapor']['tidak_hadir']++;
            } else {
                $summary['pelapor']['pending']++;
            }

            // Count terlapor responses
            if ($session->konfirmasi_terlapor === 'hadir') {
                $summary['terlapor']['hadir']++;
            } elseif ($session->konfirmasi_terlapor === 'tidak_hadir') {
                $summary['terlapor']['tidak_hadir']++;
            } else {
                $summary['terlapor']['pending']++;
            }
        }

        // Check unresponsiveness
        $summary['pelapor']['unresponsive'] = $this->isPelaporUnresponsive();
        $summary['terlapor']['unresponsive'] = $this->isTerlaporUnresponsive();

        return $summary;
    }
}
