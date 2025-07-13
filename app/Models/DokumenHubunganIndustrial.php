<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenHubunganIndustrial extends Model
{
    protected $table = 'dokumen_hubungan_industrial';
    protected $primaryKey = 'dokumen_hi_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'dokumen_hi_id',
    ];
}
