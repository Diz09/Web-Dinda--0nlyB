<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade')->nullable();
            $table->enum('kategori', ['pemasukan', 'pengeluaran']);
            $table->dateTime('waktu_transaksi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
