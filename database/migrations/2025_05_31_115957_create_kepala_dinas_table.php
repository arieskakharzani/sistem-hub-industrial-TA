// database/migrations/xxxx_create_kepala_dinas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kepala_dinas', function (Blueprint $table) {
            $table->id('kepala_dinas_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nama_kepala_dinas');
            $table->string('nip');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kepala_dinas');
    }
};
