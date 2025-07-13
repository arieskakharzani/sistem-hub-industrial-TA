<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'keterangan_pekerja',
        'keterangan_pengusaha',
        'pertimbangan_hukum',
        'isi_anjuran',
        'status_approval',
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

    public function dokumenHI()
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }
}
