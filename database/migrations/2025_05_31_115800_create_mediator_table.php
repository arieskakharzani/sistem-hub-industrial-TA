// database/migrations/xxxx_create_mediator_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mediator', function (Blueprint $table) {
            $table->uuid('mediator_id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('nama_mediator');
            $table->string('nip');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mediator');
    }
};
