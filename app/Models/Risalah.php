<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Risalah extends Model
{
    protected $table = 'risalah';
    protected $primaryKey = 'risalah_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'kesimpulan_klarifikasi' => 'string',
    ];

    protected $fillable = [
        'risalah_id',
        'jadwal_id',
        'jenis_risalah',
        'nama_perusahaan',
        'jenis_usaha',
        'alamat_perusahaan',
        'nama_pekerja',
        'alamat_pekerja',
        'tanggal_perundingan',
        'tempat_perundingan',
        'pokok_masalah',
        'arahan_mediator',
        'kesimpulan_klarifikasi',
        'pendapat_pekerja',
        'pendapat_pengusaha',
        'kesimpulan_penyelesaian',
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
}
