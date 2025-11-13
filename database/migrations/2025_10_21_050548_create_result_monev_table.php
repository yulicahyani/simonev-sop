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
        Schema::create('result_monev', function (Blueprint $table) {
            $table->id();
            $table->integer('sop_id');
            $table->string('unit_code');
            $table->date('tgl_pengisian_f01')->nullable();
            $table->date('tgl_pengisian_f02')->nullable();
            $table->integer('periode_monev_id');
            $table->text('nama_pelaksana_sop')->nullable();
            $table->text('ttd_path_pelaksana_sop')->nullable();
            $table->longText('ttd_base64_pelaksana_sop')->nullable();
            $table->text('nama_evaluator_sop')->nullable();
            $table->text('ttd_path_evaluator_sop')->nullable();
            $table->longText('ttd_base64_evaluator_sop')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_monev');
    }
};
