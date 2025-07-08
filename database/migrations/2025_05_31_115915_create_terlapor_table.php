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
            $table->uuid('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('nama_terlapor', 255);
            $table->text('alamat_kantor_cabang');
            $table->string('email_terlapor', 100);
            $table->string('no_hp_terlapor', 15)->nullable();

            $table->uuid('created_by_mediator_id')->nullable();
            $table->foreign('created_by_mediator_id')->nullable()
                ->references('mediator_id')->on('mediator')
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
