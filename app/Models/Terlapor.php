<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terlapor extends Model
{
    use SoftDeletes;

    protected $table = 'terlapor';
    protected $primaryKey = 'terlapor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'terlapor_id',
        'user_id',
        'nama_terlapor',
        'alamat_kantor_cabang',
        'email_terlapor',
        'no_hp_terlapor',
        'has_account',
        'is_active',
        'account_created_at',
        'last_login_at',
        'created_by_mediator_id',
        'total_pengaduan',
        'last_pengaduan_at'
    ];

    protected $casts = [
        'has_account' => 'boolean',
        'is_active' => 'boolean',
        'account_created_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_pengaduan_at' => 'datetime',
        'total_pengaduan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->terlapor_id)) {
                $model->terlapor_id = (string) Str::uuid();
            }
        });
    }

    // Relationships
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

    // Account Management Methods
    public function createAccount($userId, $mediatorId)
    {
        $this->update([
            'user_id' => $userId,
            'has_account' => true,
            'is_active' => true,
            'account_created_at' => now(),
            'created_by_mediator_id' => $mediatorId
        ]);
    }

    public function deactivateAccount()
    {
        $this->update(['is_active' => false]);

        if ($this->user) {
            $this->user->update(['is_active' => false]);
        }
    }

    public function activateAccount()
    {
        $this->update(['is_active' => true]);

        if ($this->user) {
            $this->user->update(['is_active' => true]);
        }
    }

    public function recordLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    // Pengaduan Management Methods
    public function recordPengaduan()
    {
        $this->increment('total_pengaduan');
        $this->update(['last_pengaduan_at' => now()]);
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeHasAccount($query)
    {
        return $query->where('has_account', true);
    }

    public function scopeNoAccount($query)
    {
        return $query->where('has_account', false);
    }

    public function scopeByMediator($query, $mediatorId)
    {
        return $query->where('created_by_mediator_id', $mediatorId);
    }

    // Helper Methods
    public static function findByCompanyInfo($namaPerusahaan, $email)
    {
        return static::where('nama_terlapor', $namaPerusahaan)
            ->where('email_terlapor', $email)
            ->first();
    }

    public function isAccountActive()
    {
        return $this->has_account && $this->is_active;
    }

    public function canCreateAccount()
    {
        return !$this->has_account;
    }
}
