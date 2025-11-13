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
        Schema::create('user_mapping', function (Blueprint $table) {
            $table->id();
            $table->integer('user_role_id');
            $table->integer('user_id');
            $table->string('role_code');
            $table->string('unit_code');
            $table->integer('sop_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mapping');
    }
};
