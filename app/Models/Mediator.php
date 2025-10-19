<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Mediator extends Model
{
    protected $table = 'mediator';
    protected $primaryKey = 'mediator_id';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'mediator_id',
        'user_id',
        'nama_mediator',
        'nip',
        'sk_file_path',
        'sk_file_name',
        'sk_file_size',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejection_date'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejection_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->mediator_id)) {
                $model->mediator_id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'mediator_id', 'mediator_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'mediator_id', 'mediator_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}
