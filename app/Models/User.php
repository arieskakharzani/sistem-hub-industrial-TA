<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email',
        'password',
        'role',
        'is_active', // Tambahkan kolom is_active
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean', // Tambahkan cast untuk is_active
        ];
    }

    // Relationships
    public function pelapor()
    {
        return $this->hasOne(Pelapor::class, 'user_id', 'user_id');
    }

    public function terlapor()
    {
        return $this->hasOne(Terlapor::class, 'user_id', 'user_id');
    }

    public function mediator()
    {
        return $this->hasOne(Mediator::class, 'user_id', 'user_id');
    }

    public function kepalaDinas()
    {
        return $this->hasOne(KepalaDinas::class, 'user_id', 'user_id');
    }

    // Helper method untuk mendapatkan role
    public function getRole()
    {
        // Prioritas 1: Cek dari kolom role di tabel users (jika ada)
        if (isset($this->role) && $this->role) {
            return $this->role;
        }

        // Prioritas 2: Cek dari relationship (fallback)
        if ($this->pelapor) return 'pelapor';
        if ($this->terlapor) return 'terlapor';
        if ($this->mediator) return 'mediator';
        if ($this->kepalaDinas) return 'kepala_dinas';

        return null;
    }

    // Helper method untuk mendapatkan profile
    public function getProfile()
    {
        switch ($this->getRole()) {
            case 'pelapor':
                return $this->pelapor;
            case 'terlapor':
                return $this->terlapor;
            case 'mediator':
                return $this->mediator;
            case 'kepala_dinas':
                return $this->kepalaDinas;
            default:
                return null;
        }
    }

    // Helper method untuk mendapatkan nama
    public function getName()
    {
        $profile = $this->getProfile();

        if (!$profile) return null;

        // Mapping nama dari berbagai tabel
        if (isset($profile->nama_pelapor)) return $profile->nama_pelapor;  // pelapor
        if (isset($profile->nama_mediator)) return $profile->nama_mediator; // mediator
        if (isset($profile->nama_kepala_dinas)) return $profile->nama_kepala_dinas; // kepala_dinas (sesuai migration)
        if (isset($profile->nama_terlapor)) return $profile->nama_terlapor; // terlapor

        return null;
    }

    // Load profile untuk mencegah N+1 queries
    public function loadProfile()
    {
        return $this->load(['pelapor', 'terlapor', 'mediator', 'kepalaDinas']);
    }

    // Role checker methods
    public function isPelapor()
    {
        return $this->getRole() === 'pelapor';
    }

    public function isTerlapor()
    {
        return $this->getRole() === 'terlapor';
    }

    public function isMediator()
    {
        return $this->getRole() === 'mediator';
    }

    public function isKepalaDinas()
    {
        return $this->getRole() === 'kepala_dinas';
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk user berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
