<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_laporan',
        'perihal',
        'masa_kerja',
        'kontak_pekerja',
        'nama_perusahaan',
        'kontak_perusahaan',
        'alamat_kantor',
        'narasi_kasus',
        'catatan_tambahan',
        'dokumen',
        'status'
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'dokumen' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getNamaPekerjaAttribute()
    {
        return $this->user ? $this->user->name : '-';
    }

    public function getNpkAttribute()
    {
        return $this->user ? $this->user->npk : '-';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Menunggu</span>',
            'diproses' => '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Diproses</span>',
            'selesai' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Selesai</span>',
            'ditolak' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Ditolak</span>',
        ];

        return $badges[$this->status] ?? '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unknown</span>';
    }

    public function getFormattedTanggalLaporanAttribute()
    {
        return Carbon::parse($this->tanggal_laporan)->format('d/m/Y');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    // Methods
    public function getDokumenList()
    {
        if (!$this->dokumen) {
            return [];
        }

        return is_string($this->dokumen) ? json_decode($this->dokumen, true) : $this->dokumen;
    }

    public function hasDokumen()
    {
        $dokumen = $this->getDokumenList();
        return !empty($dokumen);
    }

    public function canBeEdited()
    {
        return $this->status === 'pending';
    }
}
