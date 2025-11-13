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
        Schema::create('periode_monev', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->year('periode_year');
            $table->text('description');
            $table->string('status')->default('inactive'); // inactive => Tidak Aktif; active => Aktif;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_monev');
    }
};
