<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class JadwalMediasi extends Model
{
    protected $table = 'jadwal_mediasi';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'pengaduan_id',
        'mediator_id',
        'tanggal_mediasi',
        'waktu_mediasi',
        'tempat_mediasi',
        'status_jadwal',
        'catatan_jadwal',
        'hasil_mediasi'
    ];

    protected $casts = [
        'tanggal_mediasi' => 'date',
        'waktu_mediasi' => 'datetime:H:i',
    ];

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
        return $query->whereDate('tanggal_mediasi', today());
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
}
