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
        Schema::create('konsultasi_online', function (Blueprint $table) {
            $table->id();
            $table->text('nama_sop');
            $table->string('unit_code');
            $table->integer('user_id');
            $table->text('create_by');
            $table->string('status'); //Selesai atau Proses Revisi
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi_online');
    }
};
