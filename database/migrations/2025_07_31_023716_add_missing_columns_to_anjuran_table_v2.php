<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anjuran', function (Blueprint $table) {
            // Tambahkan kolom yang mungkin hilang
            if (!Schema::hasColumn('anjuran', 'nomor_anjuran')) {
                $table->string('nomor_anjuran')->nullable()->after('anjuran_id');
            }
            if (!Schema::hasColumn('anjuran', 'tanggal_anjuran')) {
                $table->date('tanggal_anjuran')->nullable()->after('nomor_anjuran');
            }
            if (!Schema::hasColumn('anjuran', 'status_approval')) {
                $table->enum('status_approval', ['draft', 'pending_kepala_dinas', 'approved', 'rejected', 'published'])->default('draft')->after('isi_anjuran');
            }
            if (!Schema::hasColumn('anjuran', 'approved_by_kepala_dinas_at')) {
                $table->timestamp('approved_by_kepala_dinas_at')->nullable()->after('status_approval');
            }
            if (!Schema::hasColumn('anjuran', 'rejected_by_kepala_dinas_at')) {
                $table->timestamp('rejected_by_kepala_dinas_at')->nullable()->after('approved_by_kepala_dinas_at');
            }
            if (!Schema::hasColumn('anjuran', 'notes_kepala_dinas')) {
                $table->text('notes_kepala_dinas')->nullable()->after('rejected_by_kepala_dinas_at');
            }
            if (!Schema::hasColumn('anjuran', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('notes_kepala_dinas');
            }
            if (!Schema::hasColumn('anjuran', 'deadline_response_at')) {
                $table->timestamp('deadline_response_at')->nullable()->after('published_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_anjuran',
                'tanggal_anjuran',
                'status_approval',
                'approved_by_kepala_dinas_at',
                'rejected_by_kepala_dinas_at',
                'notes_kepala_dinas',
                'published_at',
                'deadline_response_at'
            ]);
        });
    }
};
