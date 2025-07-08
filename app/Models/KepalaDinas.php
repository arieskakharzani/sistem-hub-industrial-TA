<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class KepalaDinas extends Model
{
    protected $table = 'kepala_dinas';
    protected $primaryKey = 'kepala_dinas_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kepala_dinas_id',
        'user_id',
        'nama_kepala_dinas',
        'nip'
    ];

    // Auto-generate UUID saat membuat akun kepala dinas baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kepala_dinas_id)) {
                $model->kepala_dinas_id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
