<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LaporanHasilMediasi extends Model
{
    protected $table = 'laporan_hasil_mediasi';
    protected $primaryKey = 'laporan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'laporan_id',
        'dokumen_hi_id',
        'tanggal_penerimaan_pengaduan',
        'nama_pekerja',
        'alamat_pekerja',
        'upah_terakhir',
        'masa_kerja',
        'nama_perusahaan',
        'alamat_perusahaan',
        'jenis_usaha',
        'waktu_penyelesaian_mediasi',
        'permasalahan',
        'pendapat_pekerja',
        'pendapat_pengusaha',
        'pendapat_saksi',
        'upaya_penyelesaian',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->laporan_id)) {
                $model->laporan_id = (string) Str::uuid();
            }
        });
    }

    public function dokumenHI()
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }
}
