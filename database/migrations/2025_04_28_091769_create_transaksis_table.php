<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('pengeluaran_id')->nullable()->constrained('pengeluarans')->onDelete('cascade');
            $table->foreignId('pemasukan_id')->nullable()->constrained('pemasukans')->onDelete('cascade');
            $table->integer('jumlahRp');
            $table->integer('qtyHistori')->default(0);
            $table->string('satuan')->default('kg');
            $table->dateTime('waktu_transaksi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
