<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('anjuran', function (Blueprint $table) {
            // Kolom untuk sistem approval
            $table->enum('status_approval', ['draft', 'pending_kepala_dinas', 'approved', 'rejected', 'published'])->default('draft')->after('isi_anjuran');
            $table->timestamp('approved_by_kepala_dinas_at')->nullable()->after('status_approval');
            $table->timestamp('rejected_by_kepala_dinas_at')->nullable()->after('approved_by_kepala_dinas_at');
            $table->text('notes_kepala_dinas')->nullable()->after('rejected_by_kepala_dinas_at');
            $table->timestamp('published_at')->nullable()->after('notes_kepala_dinas');
            $table->timestamp('deadline_response_at')->nullable()->after('published_at');
        });
    }

    public function down()
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn([
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
