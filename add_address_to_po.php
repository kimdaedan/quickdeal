<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('purchase_orders', 'alamat_detail')) {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->text('alamat_detail')->nullable()->after('detail_project');
        });
        echo "Success: Added 'alamat_detail' column to 'purchase_orders' table.\n";
    } else {
        echo "Column 'alamat_detail' already exists in 'purchase_orders' table.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
