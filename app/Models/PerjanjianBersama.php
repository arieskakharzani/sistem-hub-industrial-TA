<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PerjanjianBersama extends Model
{
    protected $table = 'perjanjian_bersama';
    protected $primaryKey = 'perjanjian_bersama_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'perjanjian_bersama_id',
        'dokumen_hi_id',
        'nama_pengusaha',
        'jabatan_pengusaha',
        'perusahaan_pengusaha',
        'alamat_pengusaha',
        'nama_pekerja',
        'jabatan_pekerja',
        'perusahaan_pekerja',
        'alamat_pekerja',
        'isi_kesepakatan',
        'nomor_perjanjian',
        'tanggal_perjanjian',
        'ttd_pekerja',
        'ttd_pengusaha',
        'ttd_mediator',
        'tanggal_ttd_pekerja',
        'tanggal_ttd_pengusaha',
        'tanggal_ttd_mediator',
        'signature_pekerja',
        'signature_pengusaha',
        'signature_mediator'
    ];

    protected $casts = [
        'tanggal_perjanjian' => 'datetime',
        'ttd_pekerja' => 'boolean',
        'ttd_pengusaha' => 'boolean',
        'ttd_mediator' => 'boolean',
        'tanggal_ttd_pekerja' => 'datetime',
        'tanggal_ttd_pengusaha' => 'datetime',
        'tanggal_ttd_mediator' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->perjanjian_bersama_id)) {
                $model->perjanjian_bersama_id = (string) Str::uuid();
            }
        });
    }

    public function dokumenHI(): BelongsTo
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    // Helper methods untuk tanda tangan
    public function isSignedByPekerja(): bool
    {
        return $this->ttd_pekerja;
    }

    public function isSignedByPengusaha(): bool
    {
        return $this->ttd_pengusaha;
    }

    public function isSignedByMediator(): bool
    {
        return $this->ttd_mediator;
    }

    public function isFullySigned(): bool
    {
        return $this->ttd_pekerja && $this->ttd_pengusaha && $this->ttd_mediator;
    }

    public function getSignatureStatus(): string
    {
        $status = [];
        
        if (!$this->ttd_pekerja) {
            $status[] = 'Menunggu tanda tangan Pekerja';
        }
        if (!$this->ttd_pengusaha && $this->ttd_pekerja) {
            $status[] = 'Menunggu tanda tangan Pengusaha';
        }
        if (!$this->ttd_mediator && $this->ttd_pekerja && $this->ttd_pengusaha) {
            $status[] = 'Menunggu tanda tangan Mediator';
        }
        if ($this->isFullySigned()) {
            return 'Sudah ditandatangani semua pihak';
        }

        return implode(', ', $status);
    }
}
