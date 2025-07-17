<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailKlarifikasi extends Model
{
    protected $table = 'detail_klarifikasi';
    protected $primaryKey = 'detail_klarifikasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'kesimpulan_klarifikasi' => 'string',
    ];

    protected $fillable = [
        'detail_klarifikasi_id',
        'risalah_id',
        'arahan_mediator',
        'kesimpulan_klarifikasi',
    ];

    public function risalah(): HasOne
    {
        return $this->hasOne(Risalah::class, 'risalah_id', 'risalah_id');
    }
}
