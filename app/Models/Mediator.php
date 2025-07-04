<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mediator extends Model
{
    protected $table = 'mediator';
    protected $primaryKey = 'mediator_id';

    protected $fillable = [
        'user_id',
        'nama_mediator',
        'nip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function jadwalMediasi()
    {
        return $this->hasMany(JadwalMediasi::class, 'mediator_id', 'mediator_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'mediator_id', 'mediator_id');
    }
}
