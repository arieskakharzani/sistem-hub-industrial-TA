<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Risalah extends Model
{
    protected $table = 'risalah';
    protected $primaryKey = 'risalah_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'risalah_id',
        'jadwal_id',
        'dokumen_hi_id',
        'jenis_risalah',
        'nama_perusahaan',
        'jenis_usaha',
        'alamat_perusahaan',
        'nama_pekerja',
        'alamat_pekerja',
        'tanggal_perundingan',
        'tempat_perundingan',
        'pokok_masalah',
        'pendapat_pekerja',
        'pendapat_pengusaha',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->risalah_id)) {
                $model->risalah_id = (string) Str::uuid();
            }
        });
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }

    //Relasi ke dokumen hubungan industrial (many-to-one)
    public function dokumenHI(): BelongsTo
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    //Relasi ke detail klarifikasi
    public function detailKlarifikasi(): HasOne
    {
        return $this->hasOne(DetailKlarifikasi::class, 'risalah_id', 'risalah_id');
    }

    //Relasi ke detail penyelesaian
    public function detailPenyelesaian(): HasOne
    {
        return $this->hasOne(DetailPenyelesaian::class, 'risalah_id', 'risalah_id');
    }
}
