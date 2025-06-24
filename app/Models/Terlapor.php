<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terlapor extends Model
{
    protected $table = 'terlapor';
    protected $primaryKey = 'terlapor_id';

    protected $fillable = [
        'user_id',
        'nama_terlapor',
        'alamat_kantor_cabang',
        'email_terlapor',
        'no_hp_terlapor',
        'created_by_mediator_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function mediator()
    {
        return $this->belongsTo(Mediator::class, 'created_by_mediator_id', 'mediator_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'terlapor_id', 'terlapor_id');
    }
}
