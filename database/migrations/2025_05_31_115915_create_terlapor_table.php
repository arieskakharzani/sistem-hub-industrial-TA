<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terlapor', function (Blueprint $table) {
            $table->uuid('terlapor_id')->primary();
            $table->uuid('user_id')->nullable(); // Nullable karena tidak semua terlapor punya akun
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            
            // Data perusahaan - ini yang jadi identifier unik
            $table->string('nama_terlapor', 255);
            $table->text('alamat_kantor_cabang');
            $table->string('email_terlapor', 100);
            $table->string('no_hp_terlapor', 15)->nullable();

            // Data akun dan status
            $table->boolean('has_account')->default(false); // Flag untuk menandai apakah punya akun
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif
            $table->timestamp('account_created_at')->nullable(); // Kapan akun dibuat
            $table->timestamp('last_login_at')->nullable(); // Terakhir login

            // Mediator yang mengelola
            $table->uuid('created_by_mediator_id')->nullable();
            $table->foreign('created_by_mediator_id')
                ->references('mediator_id')
                ->on('mediator')
                ->onDelete('set null');

            // Tracking
            $table->integer('total_pengaduan')->default(0); // Jumlah pengaduan yang masuk
            $table->timestamp('last_pengaduan_at')->nullable(); // Pengaduan terakhir kapan

            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete

            // Composite unique untuk identifikasi perusahaan
            $table->unique(['nama_terlapor', 'email_terlapor'], 'terlapor_company_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('terlapor');
    }
};
