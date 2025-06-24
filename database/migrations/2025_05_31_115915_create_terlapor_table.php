<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terlapor', function (Blueprint $table) {
            $table->id('terlapor_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nama_terlapor', 255);
            $table->text('alamat_kantor_cabang');
            $table->string('email_terlapor', 100);
            $table->string('no_hp_terlapor', 15)->nullable();

            $table->foreignId('created_by_mediator_id')->nullable()
                ->constrained('mediator', 'mediator_id')
                ->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terlapor');
    }
};
