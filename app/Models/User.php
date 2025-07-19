<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'email',
        'password',
        'roles',
        'active_role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'roles' => 'array',
    ];

    // Auto-generate UUID saat membuat user baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = (string) Str::uuid();
            }
            if (empty($model->roles)) {
                $model->roles = ['pelapor'];
            }
            if (empty($model->active_role)) {
                $model->active_role = $model->roles[0];
            }
        });
    }

    // Override method untuk UUID
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function getAuthIdentifier()
    {
        return $this->user_id;
    }

    // Method untuk debugging
    public function getKeyName()
    {
        return $this->primaryKey;
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

    // Role management methods
    public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            $roles = $this->roles;
            $roles[] = $role;
            $this->update(['roles' => $roles]);
        }
    }

    public function removeRole($role)
    {
        if (in_array($role, $this->roles)) {
            $roles = array_diff($this->roles, [$role]);
            $this->update(['roles' => array_values($roles)]);
            
            // If active role is removed, set to first available role
            if ($this->active_role === $role && !empty($roles)) {
                $this->setActiveRole($roles[0]);
            }
        }
    }

    public function setActiveRole($role)
    {
        if (in_array($role, $this->roles)) {
            $this->update(['active_role' => $role]);
            return true;
        }
        return false;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function hasAnyRole($roles)
    {
        return !empty(array_intersect($this->roles, (array) $roles));
    }

    public function hasAllRoles($roles)
    {
        return !array_diff((array) $roles, $this->roles);
    }

    // Helper method untuk mendapatkan role aktif
    public function getRole()
    {
        return $this->active_role;
    }

    // Helper method untuk mendapatkan profile berdasarkan role aktif
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
        if (isset($profile->nama_kepala_dinas)) return $profile->nama_kepala_dinas; // kepala_dinas
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
        return $query->whereJsonContains('roles', $role);
    }

    /**
     * Scope untuk user dengan role aktif tertentu
     */
    public function scopeByActiveRole($query, $role)
    {
        return $query->where('active_role', $role);
    }
}
