<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pelapor', function (Blueprint $table) {
            $table->uuid('pelapor_id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('nama_pelapor');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('perusahaan');
            $table->string('npk');
            $table->string('email');
            // $table->timestamp('email_verified_at')->nullable();
            // $table->enum('role', ['pelapor'])->default('pelapor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelapor');
    }
};
