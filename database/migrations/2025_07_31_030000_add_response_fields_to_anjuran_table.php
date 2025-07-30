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
            // Response fields for pelapor and terlapor
            $table->enum('response_pelapor', ['pending', 'setuju', 'tidak_setuju'])->default('pending')->after('deadline_response_at');
            $table->text('response_note_pelapor')->nullable()->after('response_pelapor');
            $table->timestamp('response_at_pelapor')->nullable()->after('response_note_pelapor');

            $table->enum('response_terlapor', ['pending', 'setuju', 'tidak_setuju'])->default('pending')->after('response_at_pelapor');
            $table->text('response_note_terlapor')->nullable()->after('response_terlapor');
            $table->timestamp('response_at_terlapor')->nullable()->after('response_note_terlapor');

            // Overall response status
            $table->enum('overall_response_status', ['pending', 'both_agree', 'both_disagree', 'mixed'])->default('pending')->after('response_at_terlapor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn([
                'response_pelapor',
                'response_note_pelapor',
                'response_at_pelapor',
                'response_terlapor',
                'response_note_terlapor',
                'response_at_terlapor',
                'overall_response_status'
            ]);
        });
    }
};
