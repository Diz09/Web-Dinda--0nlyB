<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('barang_pendukungs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->timestamps();
        });

        // Schema::table('suppliers', function (Blueprint $table) {
        //     $table->string('no_rekening')->nullable()->after('no_tlp');
        // });

        // Schema::table('karyawans', function (Blueprint $table) {
        //     $table->string('no_telepon')->nullable()->after('jenis_kelamin');
        // });



        Schema::dropIfExists('barang_mentahs');
        Schema::dropIfExists('barang_dasars');
    }

    public function down()
    {
        Schema::create('barang_mentahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->timestamps();
        });

        Schema::create('barang_dasars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->timestamps();
        });

        Schema::dropIfExists('barang_bahan_pendukungs');
    }

};
