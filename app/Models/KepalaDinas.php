<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaDinas extends Model
{
    protected $table = 'kepala_dinas';
    protected $primaryKey = 'kepala_dinas_id';

    protected $fillable = [
        'user_id',
        'nama_kepala_dinas',
        'nip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
