<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduans';
    protected $primaryKey = 'pengaduan_id';

    protected $fillable = [
        'pelapor_id',
        'tanggal_laporan',
        'perihal',
        'masa_kerja',
        'kontak_pekerja',
        'nama_perusahaan',
        'kontak_perusahaan',
        'alamat_kantor_cabang',
        'narasi_kasus',
        'catatan_tambahan',
        'lampiran',
        'status',
        'mediator_id',
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

    /**
     * Get the pelapor that owns the pengaduan.
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pelapor::class, 'pelapor_id', 'pelapor_id');
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
}
