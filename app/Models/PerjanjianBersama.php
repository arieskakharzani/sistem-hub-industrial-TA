<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PerjanjianBersama extends Model
{
    protected $table = 'perjanjian_bersama';
    protected $primaryKey = 'perjanjian_bersama_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'perjanjian_bersama_id',
        'dokumen_hi_id',
        'nama_pengusaha',
        'jabatan_pengusaha',
        'perusahaan_pengusaha',
        'alamat_pengusaha',
        'nama_pekerja',
        'jabatan_pekerja',
        'perusahaan_pekerja',
        'alamat_pekerja',
        'isi_kesepakatan',
        'tanggal_perjanjian',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tanggal_perjanjian' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->perjanjian_bersama_id)) {
                $model->perjanjian_bersama_id = (string) Str::uuid();
            }
        });
    }

    public function dokumenHI(): BelongsTo
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function anjuran()
    {
        return $this->dokumenHI->anjuran()->where('status_approval', 'published')->first();
    }
}
