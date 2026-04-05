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
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
            
            $table->string('no_surat');
            $table->date('tanggal');
            
            $table->string('sumber_pengirim')->nullable();
            
            $table->string('penerima_nama')->nullable();
            $table->string('penerima_instansi')->nullable();
            $table->text('penerima_alamat')->nullable();
            
            $table->text('catatan_pengiriman')->nullable();
            
            // Path Gambar Dokumentasi
            $table->string('before_image_path')->nullable();
            $table->string('after_image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
