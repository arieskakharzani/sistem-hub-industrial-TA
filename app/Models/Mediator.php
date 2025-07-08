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
        'nip'
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

    public function jadwalMediasi()
    {
        return $this->hasMany(JadwalMediasi::class, 'mediator_id', 'mediator_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'mediator_id', 'mediator_id');
    }
}
