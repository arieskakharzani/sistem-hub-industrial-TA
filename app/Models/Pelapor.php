<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Pelapor extends Model
{
    protected $table = 'pelapor';
    protected $primaryKey = 'pelapor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pelapor_id',
        'user_id',
        'nama_pelapor',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'perusahaan',
        'npk',
        'email',
        'email_verified_at',
        // 'role'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'email_verified_at' => 'datetime',
        ];
    }

    // Auto-generate UUID saat membuat pelapor baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->pelapor_id)) {
                $model->pelapor_id = (string) Str::uuid();
            }
        });
    }

    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'pelapor_id', 'pelapor_id');
    }
}
