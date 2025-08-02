<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BukuRegisterPerselisihan extends Model
{
    protected $table = 'buku_register_perselisihan';
    protected $primaryKey = 'buku_register_perselisihan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'buku_register_perselisihan_id',
        'dokumen_hi_id',
        'tanggal_pencatatan',
        'pihak_mencatat',
        'pihak_pekerja',
        'pihak_pengusaha',
        'perselisihan_hak',
        'perselisihan_kepentingan',
        'perselisihan_phk',
        'perselisihan_sp_sb',
        'penyelesaian_bipartit',
        'penyelesaian_klarifikasi',
        'penyelesaian_mediasi',
        'penyelesaian_pb',
        'penyelesaian_anjuran',
        'penyelesaian_risalah',
        'tindak_lanjut_phi',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->buku_register_perselisihan_id)) {
                $model->buku_register_perselisihan_id = (string) Str::uuid();
            }
        });
    }

    public function dokumenHI()
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function pengaduan()
    {
        return $this->dokumenHI ? $this->dokumenHI->pengaduan() : null;
    }
}
