<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('purchase_orders', 'alamat_detail')) {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->text('alamat_detail')->nullable();
        });
    }
} catch (\Exception $e) {}
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProductOfferController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BastController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecapController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Halaman Utama / Landing Page)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Jika user sudah login, arahkan langsung ke dashboard sesuai role
    if (auth()->check()) {
        if (auth()->user()->role === 'client') {
            return redirect()->route('client.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('front.landing'); // Pastikan file resources/views/landing.blade.php sudah ada
})->name('front.landing');

Route::get('/penawaran-public', [\App\Http\Controllers\PublicOfferController::class, 'index'])->name('front.penawaran.index');
Route::get('/penawaran-public/{id}', [\App\Http\Controllers\PublicOfferController::class, 'show'])->name('front.penawaran.show');
Route::post('/penawaran-public/{id}/negosiasi', [\App\Http\Controllers\PublicOfferController::class, 'storeNegotiation'])->name('front.penawaran.negotiate');



/*
|--------------------------------------------------------------------------
| 2. AUTH ROUTES (Login & Logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


/*
|--------------------------------------------------------------------------
| 3. PROTECTED ROUTES (Hanya Bisa Diakses Setelah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'client') {
            return redirect()->route('client.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // --- CLIENT DASHBOARD & PO ---
    Route::get('/client/dashboard', function () {
        if (auth()->user()->role !== 'client') {
            return redirect()->route('dashboard');
        }

        $userName = auth()->user()->name;
        $userId = auth()->id();

        // Fetch unpaid invoices (status != 'paid')
        $unpaidInvoices = \App\Models\Invoice::where('nama_klien', $userName)
            ->where('status', '!=', 'paid')
            ->get();

        $totalUnpaidAmount = $unpaidInvoices->sum('sisa_pembayaran');
        $unpaidCount = $unpaidInvoices->count();

        // Fetch 5 most recent invoices
        $recentInvoices = \App\Models\Invoice::where('nama_klien', $userName)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch 5 most recent Purchase Orders
        $recentPOs = \App\Models\PurchaseOrder::with('offer')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalPOsCount = \App\Models\PurchaseOrder::where('user_id', $userId)->count();
        $pendingPOsCount = \App\Models\PurchaseOrder::where('user_id', $userId)->where('status', 'pending')->count();

        return view('client.dashboard', compact(
            'totalUnpaidAmount',
            'unpaidCount',
            'recentInvoices',
            'recentPOs',
            'totalPOsCount',
            'pendingPOsCount'
        ));
    })->name('client.dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/penawaran-public/{id}/buat-po', [\App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('po.create');
        Route::post('/penawaran-public/{id}/buat-po', [\App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('po.store');

        Route::get('/client/po-history', [\App\Http\Controllers\PurchaseOrderController::class, 'historyUser'])->name('client.po.history');
        Route::get('/admin/po-history', [\App\Http\Controllers\PurchaseOrderController::class, 'historyAdmin'])->name('admin.po.history');
        Route::post('/admin/po/{id}/status', [\App\Http\Controllers\PurchaseOrderController::class, 'updateStatus'])->name('admin.po.status');
        Route::get('/po/{id}/print', [\App\Http\Controllers\PurchaseOrderController::class, 'print'])->name('po.print');
        Route::get('/client/po/{id}/edit', [\App\Http\Controllers\PurchaseOrderController::class, 'edit'])->name('client.po.edit');
        Route::put('/client/po/{id}', [\App\Http\Controllers\PurchaseOrderController::class, 'update'])->name('client.po.update');
        Route::delete('/po/{id}', [\App\Http\Controllers\PurchaseOrderController::class, 'destroy'])->name('po.destroy');
        Route::post('/admin/po/{id}/create-invoice', [\App\Http\Controllers\PurchaseOrderController::class, 'createInvoice'])->name('admin.po.create_invoice');
    });

    // --- MASTER DATA PRODUK (DAFTAR HARGA) ---
    Route::prefix('daftar-harga')->name('harga.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/tambah', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // --- MENU PENAWARAN ---
    Route::prefix('penawaran')->name('penawaran.')->group(function () {
        // Penawaran PROYEK
        Route::get('/create-project', [ProductController::class, 'createCombined'])->name('create_combined');
        Route::post('/store-project', [ProductController::class, 'storeCombined'])->name('store_combined');

        // Penawaran PRODUK
        Route::get('/create-product', [ProductOfferController::class, 'create'])->name('create_product');
        Route::post('/store-product', [ProductOfferController::class, 'store'])->name('store_product');
        Route::get('/edit-product/{offer}', [ProductOfferController::class, 'edit'])->name('edit_product');
        Route::put('/update-product/{offer}', [ProductOfferController::class, 'update'])->name('update_product');
    });

    // --- HISTORI & DETAIL PENAWARAN ---
    Route::prefix('histori-penawaran')->name('histori.')->group(function () {
        Route::get('/', [OfferController::class, 'index'])->name('index');
        Route::get('/{offer}', [OfferController::class, 'show'])->name('show');
        Route::get('/{offer}/edit', [OfferController::class, 'edit'])->name('edit');
        Route::put('/{offer}', [OfferController::class, 'update'])->name('update');
        Route::delete('/{offer}', [OfferController::class, 'destroy'])->name('destroy');
        Route::get('/{offer}/print', [OfferController::class, 'print'])->name('print');
        Route::post('/{offer}/toggle-publish', [OfferController::class, 'togglePublish'])->name('toggle_publish');
    });

    Route::delete('/negotiations/{id}', [OfferController::class, 'destroyNegotiation'])->name('negotiation.destroy');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // --- INVOICE ---
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/histori', [InvoiceController::class, 'index'])->name('histori');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::get('/create-from-offer/{offer}', [InvoiceController::class, 'createFromOffer'])->name('create_from_offer');
        Route::post('/store-from-offer', [InvoiceController::class, 'storeFromOffer'])->name('store_from_offer');
        Route::get('/show/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/edit/{invoice}', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/update/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::post('/add-payment/{invoice}', [InvoiceController::class, 'addPayment'])->name('add_payment');
        Route::post('/verify-payment/{payment}', [InvoiceController::class, 'verifyPayment'])->name('verify_payment');
        Route::delete('/reject-payment/{payment}', [InvoiceController::class, 'rejectPayment'])->name('reject_payment');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('print');
    });

    // --- BAST (Berita Acara Serah Terima) ---
    Route::prefix('bast')->name('bast.')->group(function () {
        Route::get('/histori', [BastController::class, 'index'])->name('index');
        Route::get('/show/{bast}', [BastController::class, 'show'])->name('show');
        Route::delete('/{bast}', [BastController::class, 'destroy'])->name('destroy');
        Route::get('/create/{offer}', [BastController::class, 'create'])->name('create');
        Route::post('/store/{offer}', [BastController::class, 'store'])->name('store');
        Route::get('/{id}/print', [BastController::class, 'print'])->name('print');
    });

    // --- SURAT JALAN (Untuk Produk) ---
    Route::prefix('surat-jalan')->name('surat_jalan.')->group(function () {
        Route::get('/histori', [App\Http\Controllers\SuratJalanController::class, 'index'])->name('index');
        Route::get('/create/{offer}', [App\Http\Controllers\SuratJalanController::class, 'create'])->name('create');
        Route::post('/store/{offer}', [App\Http\Controllers\SuratJalanController::class, 'store'])->name('store');
        Route::get('/show/{suratJalan}', [App\Http\Controllers\SuratJalanController::class, 'show'])->name('show');
        Route::get('/{id}/print', [App\Http\Controllers\SuratJalanController::class, 'print'])->name('print');
        Route::delete('/{suratJalan}', [App\Http\Controllers\SuratJalanController::class, 'destroy'])->name('destroy');
    });

    //--- Pembuatan REKAP
    Route::get('/recap/create/{offer}', [RecapController::class, 'create'])->name('histori.recap');
    Route::post('/recap/store', [RecapController::class, 'store'])->name('recap.store');
    Route::get('/recap/history', [RecapController::class, 'index'])->name('recap.index');
    Route::get('/recap/show/{id}', [RecapController::class, 'show'])->name('recap.show');
    Route::delete('/recap/delete/{id}', [RecapController::class, 'destroy'])->name('recap.destroy');
    // Route untuk Edit & Update
    Route::get('/recap/{id}/edit', [RecapController::class, 'edit'])->name('recap.edit');
    Route::put('/recap/{id}', [RecapController::class, 'update'])->name('recap.update');

    // Route untuk Delete
    Route::delete('/recap/{id}', [RecapController::class, 'destroy'])->name('recap.destroy');

    Route::get('/recap/export/excel/{id}', [RecapController::class, 'exportExcel'])->name('recap.export.excel');
    Route::get('/recap/export/word/{id}', [RecapController::class, 'exportWord'])->name('recap.export.word');

// SPK routes removed
});



