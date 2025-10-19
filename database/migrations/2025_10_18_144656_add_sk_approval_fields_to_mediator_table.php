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
        Schema::table('mediator', function (Blueprint $table) {
            $table->string('sk_file_path', 500)->nullable()->after('nip');
            $table->string('sk_file_name', 255)->nullable()->after('sk_file_path');
            $table->integer('sk_file_size')->nullable()->after('sk_file_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('sk_file_size');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->timestamp('rejection_date')->nullable()->after('rejection_reason');

            // Foreign key untuk approved_by
            $table->foreign('approved_by')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mediator', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'sk_file_path',
                'sk_file_name',
                'sk_file_size',
                'status',
                'approved_by',
                'approved_at',
                'rejection_reason',
                'rejection_date'
            ]);
        });
    }
};
