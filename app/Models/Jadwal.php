<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'jadwal_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'jadwal_id',
        'pengaduan_id',
        'mediator_id',
        'tanggal',
        'waktu',
        'tempat',
        'jenis_jadwal',
        'sidang_ke',
        'status_jadwal',
        'catatan_jadwal',
        'hasil',
        // Konfirmasi kehadiran
        'konfirmasi_pelapor',
        'konfirmasi_terlapor',
        'tanggal_konfirmasi_pelapor',
        'tanggal_konfirmasi_terlapor',
        'catatan_konfirmasi_pelapor',
        'catatan_konfirmasi_terlapor'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i',
        'tanggal_konfirmasi_pelapor' => 'datetime',
        'tanggal_konfirmasi_terlapor' => 'datetime'
    ];

    // Auto-generate UUID saat membuat jadwal baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->jadwal_id)) {
                $model->jadwal_id = (string) Str::uuid();
            }
            // Generate nomor_jadwal otomatis
            if (empty($model->nomor_jadwal)) {
                $year = now()->year;
                $last = self::whereYear('created_at', $year)
                    ->whereNotNull('nomor_jadwal')
                    ->orderByDesc('nomor_jadwal')
                    ->first();
                $next = 1;
                if ($last && preg_match('/JDL-' . $year . '-(\\d{4})$/', $last->nomor_jadwal, $matches)) {
                    $next = intval($matches[1]) + 1;
                }
                $model->nomor_jadwal = sprintf('JDL-%d-%04d', $year, $next);
            }
        });
    }

    // Hubungan dengan tabel pengaduan
    public function pengaduan(): BelongsTo
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'pengaduan_id');
    }

    // Hubungan dengan tabel mediator
    public function mediator(): BelongsTo
    {
        return $this->belongsTo(Mediator::class, 'mediator_id', 'mediator_id');
    }

    //Relasi ke risalah
    public function risalah()
    {
        return $this->hasMany(Risalah::class, 'jadwal_id', 'jadwal_id');
    }

    public function risalahKlarifikasi()
    {
        return $this->hasOne(Risalah::class, 'jadwal_id', 'jadwal_id')->where('jenis_risalah', 'klarifikasi');
    }

    public function risalahPenyelesaian()
    {
        return $this->hasOne(Risalah::class, 'jadwal_id', 'jadwal_id')->where('jenis_risalah', 'penyelesaian');
    }

    // Relasi ke detail mediasi (semua sesi mediasi untuk pengaduan ini)
    public function detailMediasi()
    {
        return $this->hasManyThrough(
            \App\Models\DetailMediasi::class,
            \App\Models\Risalah::class,
            'jadwal_id', // Foreign key di Risalah
            'risalah_id', // Foreign key di DetailMediasi
            'jadwal_id', // Local key di Jadwal
            'risalah_id' // Local key di Risalah
        )->whereHas('risalah', function ($q) {
            $q->where('jenis_risalah', 'mediasi');
        });
    }

    // Helper untuk ambil detail mediasi terakhir (sidang_ke terbesar)
    public function detailMediasiTerakhir()
    {
        return $this->detailMediasi()->orderByDesc('sidang_ke')->first();
    }

    // Scope untuk filter berdasarkan mediator
    public function scopeByMediator($query, $mediatorId)
    {
        return $query->where('mediator_id', $mediatorId);
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_jadwal', $status);
    }

    // Scope untuk jadwal hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope untuk jadwal yang membutuhkan konfirmasi
    public function scopeMenungguKonfirmasi($query)
    {
        return $query->where('status_jadwal', 'dijadwalkan')
            ->where(function ($q) {
                $q->where('konfirmasi_pelapor', 'pending')
                    ->orWhere('konfirmasi_terlapor', 'pending');
            });
    }

    // Method untuk mengecek apakah kedua pihak sudah konfirmasi
    public function sudahDikonfirmasiSemua(): bool
    {
        return $this->konfirmasi_pelapor !== 'pending' &&
            $this->konfirmasi_terlapor !== 'pending';
    }

    // Method untuk mengecek apakah ada yang tidak hadir
    public function adaYangTidakHadir(): bool
    {
        return $this->konfirmasi_pelapor === 'tidak_hadir' ||
            $this->konfirmasi_terlapor === 'tidak_hadir';
    }

    // Method untuk mendapatkan warna badge status
    public function getStatusBadgeClass(): string
    {
        return match ($this->status_jadwal) {
            'dijadwalkan' => 'bg-blue-100 text-blue-800',
            'berlangsung' => 'bg-yellow-100 text-yellow-800',
            'selesai' => 'bg-green-100 text-green-800',
            'ditunda' => 'bg-orange-100 text-orange-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Method untuk mendapatkan warna badge konfirmasi
    public function getKonfirmasiBadgeClass($jenis): string
    {
        $konfirmasi = $jenis === 'pelapor' ? $this->konfirmasi_pelapor : $this->konfirmasi_terlapor;

        return match ($konfirmasi) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'hadir' => 'bg-green-100 text-green-800',
            'tidak_hadir' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Method untuk mendapatkan icon status
    public function getStatusIcon(): string
    {
        return match ($this->status_jadwal) {
            'dijadwalkan' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
            'berlangsung' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'selesai' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            default => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
        };
    }

    // Method untuk mendapatkan pilihan status
    public static function getStatusOptions(): array
    {
        return [
            'dijadwalkan' => 'Dijadwalkan',
            'berlangsung' => 'Berlangsung',
            'selesai' => 'Selesai',
            'ditunda' => 'Ditunda',
            'dibatalkan' => 'Dibatalkan'
        ];
    }

    //Methid untuk mendapatkan pilihan jenis jadwal
    public static function getJenisJadwalOptions(): array
    {
        return [
            'klarifikasi' => 'Klarifikasi',
            'mediasi' => 'Mediasi'
        ];
    }

    // Method untuk mendapatkan pilihan konfirmasi
    public static function getKonfirmasiOptions(): array
    {
        return [
            'pending' => 'Menunggu Konfirmasi',
            'hadir' => 'Akan Hadir',
            'tidak_hadir' => 'Tidak Dapat Hadir'
        ];
    }
}
