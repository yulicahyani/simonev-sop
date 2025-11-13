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
        Schema::create('buku_tamu_konsultasi', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code');
            $table->text('unit_nama');
            $table->text('kegiatan_konsultasi');
            $table->date('tgl_konsultasi');
            $table->text('nama');
            $table->text('jabatan');
            $table->text('ttd_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamu_konsultasi');
    }
};
