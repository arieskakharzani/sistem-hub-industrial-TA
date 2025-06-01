<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename tabel users lama ke users_backup
        Schema::rename('users', 'users_backup');

        // Buat tabel users baru
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['pelapor', 'terlapor', 'mediator', 'kepala_dinas'])->default('pelapor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Schema::rename('users_backup', 'users');
    }
};
