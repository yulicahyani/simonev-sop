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
        Schema::create('sop', function (Blueprint $table) {
            $table->id();
            $table->text('nama')->unique();
            $table->string('nomor')->unique();
            $table->date('tgl_pembuatan')->nullable();
            $table->date('tgl_revisi')->nullable();
            $table->date('tgl_efektif')->nullable();
            $table->string('unit_code');
            $table->text('filename')->nullable();
            $table->text('filepath')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sop');
    }
};
