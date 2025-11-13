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
        Schema::create('instrumen_f02', function (Blueprint $table) {
            $table->id();
            $table->text('instrumen')->unique();
            $table->string('kategori'); #ya/tidak
            $table->string('aspek'); #Evaluasi Pemenuhan SOP; Evaluasi Substansi SOP;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumen_f02');
    }
};
