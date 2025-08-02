<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Anjuran extends Model
{
    protected $table = 'anjuran';
    protected $primaryKey = 'anjuran_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'anjuran_id',
        'dokumen_hi_id',
        'kepala_dinas_id',
        'nama_pengusaha',
        'jabatan_pengusaha',
        'perusahaan_pengusaha',
        'alamat_pengusaha',
        'nama_pekerja',
        'jabatan_pekerja',
        'perusahaan_pekerja',
        'alamat_pekerja',
        'keterangan_pekerja',
        'keterangan_pengusaha',
        'pertimbangan_hukum',
        'isi_anjuran',
        'nomor_anjuran',
        'status_approval',
        'approved_by_kepala_dinas_at',
        'rejected_by_kepala_dinas_at',
        'notes_kepala_dinas',
        'published_at',
        'deadline_response_at',
        'response_pelapor',
        'response_note_pelapor',
        'response_at_pelapor',
        'response_terlapor',
        'response_note_terlapor',
        'response_at_terlapor',
        'overall_response_status'
    ];

    protected $casts = [
        'approved_by_kepala_dinas_at' => 'datetime',
        'rejected_by_kepala_dinas_at' => 'datetime',
        'published_at' => 'datetime',
        'deadline_response_at' => 'datetime',
        'response_at_pelapor' => 'datetime',
        'response_at_terlapor' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->anjuran_id)) {
                $model->anjuran_id = (string) Str::uuid();
            }
            // Generate nomor_anjuran otomatis
            if (empty($model->nomor_anjuran)) {
                $year = now()->year;
                $last = self::whereYear('created_at', $year)
                    ->whereNotNull('nomor_anjuran')
                    ->orderByDesc('nomor_anjuran')
                    ->first();
                $next = 1;
                if ($last && preg_match('/ANJURAN\/' . $year . '\/(\\d{3})$/', $last->nomor_anjuran, $matches)) {
                    $next = intval($matches[1]) + 1;
                }
                $model->nomor_anjuran = sprintf('ANJURAN/%d/%03d', $year, $next);
            }
        });
    }

    public function dokumenHI(): BelongsTo
    {
        return $this->belongsTo(DokumenHubunganIndustrial::class, 'dokumen_hi_id', 'dokumen_hi_id');
    }

    public function kepalaDinas(): BelongsTo
    {
        return $this->belongsTo(KepalaDinas::class, 'kepala_dinas_id', 'kepala_dinas_id');
    }

    // Relasi ke mediator melalui dokumenHI dan pengaduan
    public function mediator()
    {
        return $this->dokumenHI->pengaduan->mediator;
    }

    // Scopes
    public function scopePendingApproval($query)
    {
        return $query->where('status_approval', 'pending_kepala_dinas');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopePublished($query)
    {
        return $query->where('status_approval', 'published');
    }

    // Methods
    public function isPendingApproval(): bool
    {
        return $this->status_approval === 'pending_kepala_dinas';
    }

    public function isApproved(): bool
    {
        return $this->status_approval === 'approved';
    }

    public function isPublished(): bool
    {
        return $this->status_approval === 'published';
    }

    public function canBeApprovedByKepalaDinas(): bool
    {
        return $this->status_approval === 'pending_kepala_dinas';
    }

    public function canBePublishedByMediator(): bool
    {
        return $this->status_approval === 'approved';
    }

    public function getDaysUntilDeadline(): int
    {
        if (!$this->deadline_response_at) return 0;
        return max(0, now()->diffInDays($this->deadline_response_at, false));
    }

    // Helper untuk mendapatkan mediator
    public function getMediatorAttribute()
    {
        return $this->mediator();
    }

    // Response helper methods
    public function hasPelaporResponded(): bool
    {
        return $this->response_pelapor !== 'pending';
    }

    public function hasTerlaporResponded(): bool
    {
        return $this->response_terlapor !== 'pending';
    }

    public function bothPartiesResponded(): bool
    {
        return $this->hasPelaporResponded() && $this->hasTerlaporResponded();
    }

    public function isResponseDeadlinePassed(): bool
    {
        return $this->deadline_response_at && now()->isAfter($this->deadline_response_at);
    }

    public function canStillRespond(): bool
    {
        return !$this->isResponseDeadlinePassed();
    }

    public function updateOverallResponseStatus(): void
    {
        if (!$this->bothPartiesResponded()) {
            $this->overall_response_status = 'pending';
        } elseif ($this->response_pelapor === 'setuju' && $this->response_terlapor === 'setuju') {
            $this->overall_response_status = 'both_agree';
        } elseif ($this->response_pelapor === 'tidak_setuju' && $this->response_terlapor === 'tidak_setuju') {
            $this->overall_response_status = 'both_disagree';
        } else {
            $this->overall_response_status = 'mixed';
        }

        $this->save();
    }

    // Helper methods untuk status respon
    public function isBothPartiesAgree(): bool
    {
        return $this->overall_response_status === 'both_agree';
    }

    public function isBothPartiesDisagree(): bool
    {
        return $this->overall_response_status === 'both_disagree';
    }

    public function isMixedResponse(): bool
    {
        return $this->overall_response_status === 'mixed';
    }

    public function isResponseComplete(): bool
    {
        return $this->bothPartiesResponded() && !$this->isResponseDeadlinePassed();
    }

    public function canCreatePerjanjianBersama(): bool
    {
        return $this->isBothPartiesAgree();
    }

    public function canFinalizeCase(): bool
    {
        return $this->isResponseComplete() && ($this->isBothPartiesAgree() || $this->isBothPartiesDisagree());
    }

    /**
     * Cek apakah sudah ada jadwal TTD perjanjian bersama untuk anjuran ini
     */
    public function hasTtdPerjanjianBersamaSchedule(): bool
    {
        return $this->dokumenHI->pengaduan->jadwal()
            ->where('jenis_jadwal', 'ttd_perjanjian_bersama')
            ->exists();
    }

    /**
     * Ambil jadwal TTD perjanjian bersama untuk anjuran ini
     */
    public function getTtdPerjanjianBersamaSchedule()
    {
        return $this->dokumenHI->pengaduan->jadwal()
            ->where('jenis_jadwal', 'ttd_perjanjian_bersama')
            ->first();
    }
}
