<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terlapor extends Model
{
    protected $table = 'terlapor';
    protected $primaryKey = 'terlapor_id';

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat_kantor_cabang',
        'email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
