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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('nama_produk', 'nama_jasa');
            $table->string('kategori')->nullable()->after('nama_jasa');
            $table->string('satuan')->nullable()->after('kategori');
            $table->dropColumn(['performa', 'kriteria', 'hasil_akhir']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('nama_jasa', 'nama_produk');
            $table->string('performa')->nullable();
            $table->string('kriteria')->nullable();
            $table->string('hasil_akhir')->nullable();
            $table->dropColumn(['kategori', 'satuan']);
        });
    }
};
