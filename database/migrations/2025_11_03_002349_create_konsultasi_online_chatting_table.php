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
        Schema::create('konsultasi_online_chatting', function (Blueprint $table) {
            $table->id();
            $table->integer('konsultasi_online_id');
            $table->text('message_konsultasi')->nullable();
            $table->text('filename_konsultasi')->nullable();
            $table->text('filepath_konsultasi')->nullable();
            $table->integer('user_id');
            $table->string('role_code');
            $table->text('create_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi_online_chatting');
    }
};
