<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terlapor extends Model
{
    protected $table = 'terlapor';
    protected $primaryKey = 'terlapor_id';

    protected $fillable = [
        'user_id',
        'nama_terlapor',
        'alamat_kantor_cabang',
        'email_terlapor',
        'no_hp_terlapor',
        'created_by_mediator_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function mediator()
    {
        return $this->belongsTo(Mediator::class, 'created_by_mediator_id', 'mediator_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'terlapor_id', 'terlapor_id');
    }

    /**
     * Scope untuk filter terlapor aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk filter terlapor tidak aktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope untuk filter berdasarkan mediator pembuat
     */
    public function scopeByMediator($query, $mediatorId)
    {
        return $query->where('created_by_mediator_id', $mediatorId);
    }

    /**
     * Accessor untuk mendapatkan status lengkap
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            default => 'Unknown'
        };
    }

    /**
     * Accessor untuk cek apakah terlapor bisa diakses oleh mediator tertentu
     */
    public function canBeAccessedBy($mediatorId)
    {
        // Semua mediator bisa akses semua terlapor (sesuai requirement baru)
        return true;
    }

    /**
     * Accessor untuk cek apakah terlapor bisa dimanage oleh mediator tertentu
     */
    public function canBeManagedBy($mediatorId)
    {
        // Hanya mediator pembuat yang bisa manage
        return $this->created_by_mediator_id === $mediatorId;
    }
}
