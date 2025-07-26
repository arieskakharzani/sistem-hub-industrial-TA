<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DetailMediasi extends Model
{
    protected $table = 'detail_mediasi';
    protected $primaryKey = 'detail_mediasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'detail_mediasi_id',
        'risalah_id',
        'ringkasan_pembahasan',
        'kesepakatan_sementara',
        'ketidaksepakatan_sementara',
        'catatan_khusus',
        'rekomendasi_mediator',
        'status_sidang', // 'selesai', 'lanjut_sidang_berikutnya'
        'sidang_ke' // 1, 2, 3
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->detail_mediasi_id)) {
                $model->detail_mediasi_id = (string) Str::uuid();
            }
        });
    }

    public function risalah(): BelongsTo
    {
        return $this->belongsTo(Risalah::class, 'risalah_id', 'risalah_id');
    }
}
