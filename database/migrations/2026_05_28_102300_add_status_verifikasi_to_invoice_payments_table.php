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
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->string('status_verifikasi')->default('verified')->after('bukti_transfer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('status_verifikasi');
        });
    }
};
