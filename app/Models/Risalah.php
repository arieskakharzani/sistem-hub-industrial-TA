<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Risalah extends Model
{
    protected $table = 'risalah';
    protected $primaryKey = 'risalah_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'risalah_id',
        'jadwal_id',
        'dokumen_hi_id',
        'jenis_risalah',
        'nama_perusahaan',
        'jenis_usaha',
        'alamat_perusahaan',
        'nama_pekerja',
        'alamat_pekerja',
        'tanggal_perundingan',
        'tempat_perundingan',
        'pokok_masalah',
        'pendapat_pekerja',
        'pendapat_pengusaha',
        'ttd_mediator',
        'tanggal_ttd_mediator',
        'signature_mediator'
    ];

    protected $casts = [
        'tanggal_perundingan' => 'datetime',
        'ttd_mediator' => 'boolean',
        'tanggal_ttd_mediator' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->risalah_id)) {
                $model->risalah_id = (string) Str::uuid();
            }
        });
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }

    //Relasi ke dokumen hubungan industrial (many-to-one)
    public function dokumenHI(): BelongsTo
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    //Relasi ke detail klarifikasi
    public function detailKlarifikasi(): HasOne
    {
        return $this->hasOne(DetailKlarifikasi::class, 'risalah_id', 'risalah_id');
    }

    //Relasi ke detail penyelesaian
    public function detailPenyelesaian(): HasOne
    {
        return $this->hasOne(DetailPenyelesaian::class, 'risalah_id', 'risalah_id');
    }

    //Relasi ke detail mediasi
    public function detailMediasi(): HasOne
    {
        return $this->hasOne(DetailMediasi::class, 'risalah_id', 'risalah_id');
    }

    // Helper method untuk tanda tangan
    public function isSignedByMediator(): bool
    {
        return $this->ttd_mediator;
    }

    public function getSignatureStatus(): string
    {
        if ($this->isSignedByMediator()) {
            return 'Sudah ditandatangani oleh Mediator';
        }
        return 'Menunggu tanda tangan Mediator';
    }

    // Scope methods untuk filter berdasarkan jenis risalah
    public function scopeKlarifikasi($query)
    {
        return $query->where('jenis_risalah', 'klarifikasi');
    }

    public function scopeMediasi($query)
    {
        return $query->where('jenis_risalah', 'mediasi');
    }

    public function scopePenyelesaian($query)
    {
        return $query->where('jenis_risalah', 'penyelesaian');
    }

    // Helper methods untuk mengecek jenis risalah
    public function isKlarifikasi(): bool
    {
        return $this->jenis_risalah === 'klarifikasi';
    }

    public function isMediasi(): bool
    {
        return $this->jenis_risalah === 'mediasi';
    }

    public function isPenyelesaian(): bool
    {
        return $this->jenis_risalah === 'penyelesaian';
    }
}
