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
        Schema::create('gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presensi_id')->constrained()->onDelete('cascade');
            $table->decimal('total_jam',5,2);
            $table->decimal('jam_lembur',5,2)->default(0);
            $table->integer('gaji_pokok');
            $table->integer('gaji_lembur')->default(0);
            $table->integer('total_gaji');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};
