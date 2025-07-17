<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPenyelesaian extends Model
{
    protected $table = 'detail_penyelesaian';
    protected $primaryKey = 'detail_penyelesaian_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'detail_penyelesaian_id',
        'risalah_id',
        'kesimpulan_penyelesaian',
    ];

    public function risalah(): HasOne   
    {
        return $this->hasOne(Risalah::class, 'risalah_id', 'risalah_id');
    }
}
