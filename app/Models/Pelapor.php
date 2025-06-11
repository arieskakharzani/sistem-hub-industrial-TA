<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelapor extends Model
{
    protected $table = 'pelapor';
    protected $primaryKey = 'pelapor_id';

    protected $fillable = [
        'user_id',
        'nama_pelapor',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'perusahaan',
        'npk',
        'email',
        // 'email_verified_at',
        // 'role'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            // 'email_verified_at' => 'datetime',
        ];
    }

    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'pelapor_id', 'pelapor_id');
    }
}
