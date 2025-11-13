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
        Schema::create('result_monev_f02', function (Blueprint $table) {
            $table->id();
            $table->integer('result_monev_id');
            $table->integer('instrumen_id');
            $table->string('jawaban');
            $table->text('catatan')->nullable();
            $table->text('tindakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_monev_f02');
    }
};
