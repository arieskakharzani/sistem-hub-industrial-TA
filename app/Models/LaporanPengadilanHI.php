<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LaporanPengadilanHI extends Model
{
    protected $table = 'laporan_pengadilan_hi';
    protected $primaryKey = 'laporan_phi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'laporan_phi_id',
        'pengaduan_id',
        'nomor_laporan',
        'tanggal_laporan',
        'nama_pelapor',
        'alamat_pelapor',
        'nama_terlapor',
        'alamat_terlapor',
        'perihal_perselisihan',
        'pokok_permasalahan',
        'upaya_penyelesaian',
        'hasil_mediasi',
        'alasan_tidak_sepakat',
        'rekomendasi_pengadilan',
        'status_laporan',
        'tanggal_kirim',
        'file_laporan',
        'catatan_tambahan'
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'tanggal_kirim' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->laporan_phi_id)) {
                $model->laporan_phi_id = (string) Str::uuid();
            }
            // Generate nomor laporan otomatis
            if (empty($model->nomor_laporan)) {
                $year = now()->year;
                $last = self::whereYear('created_at', $year)
                    ->whereNotNull('nomor_laporan')
                    ->orderByDesc('nomor_laporan')
                    ->first();
                $next = 1;
                if ($last && preg_match('/LPH-' . $year . '-(\\d{4})$/', $last->nomor_laporan, $matches)) {
                    $next = intval($matches[1]) + 1;
                }
                $model->nomor_laporan = sprintf('LPH-%d-%04d', $year, $next);
            }
        });
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'pengaduan_id');
    }

    public function anjuran()
    {
        return $this->hasOne(Anjuran::class, 'pengaduan_id', 'pengaduan_id');
    }

    public function laporanHasilMediasi()
    {
        return $this->hasOne(LaporanHasilMediasi::class, 'pengaduan_id', 'pengaduan_id');
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status_laporan) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'sent' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted status text
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status_laporan) {
            'draft' => 'Draft',
            'submitted' => 'Terkirim',
            'sent' => 'Diterima',
            'rejected' => 'Ditolak',
            default => 'Status Tidak Dikenal'
        };
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        return $this->file_laporan ? asset('storage/' . $this->file_laporan) : null;
    }

    /**
     * Check if file exists
     */
    public function hasFile(): bool
    {
        return !empty($this->file_laporan) && file_exists(storage_path('app/public/' . $this->file_laporan));
    }

    /**
     * Constants untuk enum values
     */
    public static function getStatusOptions()
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Terkirim',
            'sent' => 'Diterima',
            'rejected' => 'Ditolak'
        ];
    }
}
