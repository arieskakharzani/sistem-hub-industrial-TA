<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Anjuran extends Model
{
    protected $table = 'anjuran';
    protected $primaryKey = 'anjuran_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'anjuran_id',
        'dokumen_hi_id',
        'kepala_dinas_id',
        'nama_pengusaha',
        'jabatan_pengusaha',
        'perusahaan_pengusaha',
        'alamat_pengusaha',
        'nama_pekerja',
        'jabatan_pekerja',
        'perusahaan_pekerja',
        'alamat_pekerja',
        'keterangan_pekerja',
        'keterangan_pengusaha',
        'pertimbangan_hukum',
        'isi_anjuran',
        'nomor_anjuran',
        'tanggal_anjuran'
    ];

    protected $casts = [
        'tanggal_anjuran' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->anjuran_id)) {
                $model->anjuran_id = (string) Str::uuid();
            }
        });
    }

    public function dokumenHI(): BelongsTo
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function kepalaDinas(): BelongsTo
    {
        return $this->belongsTo(KepalaDinas::class, 'kepala_dinas_id', 'kepala_dinas_id');
    }
}
