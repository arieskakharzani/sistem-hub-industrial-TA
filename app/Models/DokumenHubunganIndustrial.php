<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DokumenHubunganIndustrial extends Model
{
    protected $table = 'dokumen_hubungan_industrial';
    protected $primaryKey = 'dokumen_hi_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'dokumen_hi_id',
        'pengaduan_id',
        'jenis_dokumen',
        'tanggal_dokumen',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->dokumen_hi_id)) {
                $model->dokumen_hi_id = (string) Str::uuid();
            }
        });
    }

    // Relasi ke pengaduan (many-to-one)
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'pengaduan_id');
    }

    // Relasi ke risalah (one-to-many)
    public function risalah()
    {
        return $this->hasMany(Risalah::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function bukuRegister()
    {
        return $this->hasMany(BukuRegisterPerselisihan::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function perjanjianBersama()
    {
        return $this->hasMany(PerjanjianBersama::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }   

    public function anjuran()
    {
        return $this->hasMany(Anjuran::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function laporanHasilMediasi()
    {
        return $this->hasMany(LaporanHasilMediasi::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }
}
