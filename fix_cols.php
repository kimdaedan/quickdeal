<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

\Illuminate\Support\Facades\DB::statement('ALTER TABLE products CHANGE nama_jasa nama_produk VARCHAR(255)');
echo 'Fixed';
