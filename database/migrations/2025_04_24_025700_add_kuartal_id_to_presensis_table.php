<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->unsignedBigInteger('kuartal_id')->nullable()->after('karyawan_id');

            $table->foreign('kuartal_id')->references('id')->on('kuartals')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['kuartal_id']);
            $table->dropColumn('kuartal_id');
        });
    }

};
