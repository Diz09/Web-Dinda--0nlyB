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
        Schema::create('history_gaji_kloter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kloter_id');
            $table->integer('jml_karyawan');
            $table->double('total_gaji');
            $table->timestamp('waktu');
            $table->timestamps();

            $table->foreign('kloter_id')->references('id')->on('kloters')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_gaji_kloter');
    }
};
