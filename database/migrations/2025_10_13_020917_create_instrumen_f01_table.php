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
        Schema::create('instrumen_f01', function (Blueprint $table) {
            $table->id();
            $table->text('instrumen')->unique();
            $table->string('kategori'); #ya/tidak
            $table->string('aspek'); #Monitoring Pelaksanaan SOP; Evaluasi Penerapan SOP;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumen_f01');
    }
};
