<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("ALTER TABLE products CHANGE nama_produk nama_jasa VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    echo "Success changing nama_produk to nama_jasa in products table.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
