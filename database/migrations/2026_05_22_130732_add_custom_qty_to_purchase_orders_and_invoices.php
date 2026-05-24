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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->json('custom_quantities')->nullable()->after('email');
            $table->bigInteger('custom_total')->nullable()->after('custom_quantities');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('set null')->after('offer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn('purchase_order_id');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['custom_quantities', 'custom_total']);
        });
    }
};
