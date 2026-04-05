<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import Model Product
use App\Models\Offer;   // Import Model Offer
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk DB Transaction

class ProductController extends Controller
{
    // =========================================================================
    // 1. MASTER DATA PRODUK (DAFTAR HARGA)
    // =========================================================================

    // Menampilkan daftar harga (Read)
    public function index()
    {
        // Mengambil semua produk diurutkan terbaru
        $products = Product::latest()->get();
        // Pastikan view ini ada di: resources/views/harga/index.blade.php
        return view('harga.index', ['products' => $products]);
    }

    // Form tambah harga baru (Create View)
    public function create()
    {
        return view('harga.create');
    }

    // Menyimpan data harga baru (Create Action)
    public function store(Request $request)
    {
        // 1. Validasi Input (Sesuai dengan form di harga/create.blade.php)
        $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'satuan'    => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
        ]);

        // 2. Simpan ke Database
        Product::create([
            'nama_jasa' => $request->nama_jasa,
            'kategori'  => $request->kategori,
            'satuan'    => $request->satuan,
            'harga'     => $request->harga,
        ]);

        // 3. Redirect kembali ke index dengan pesan sukses
        return redirect()->route('harga.index')->with('success', 'Data harga berhasil ditambahkan!');
    }

    // Form edit harga (Edit View)
    public function edit(Product $product)
    {
        return view('harga.edit', ['product' => $product]);
    }

    // Update data harga (Update Action)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'satuan'    => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
        ]);

        $product->update([
            'nama_jasa' => $request->nama_jasa,
            'kategori'  => $request->kategori,
            'satuan'    => $request->satuan,
            'harga'     => $request->harga,
        ]);

        return redirect()->route('harga.index')->with('success', 'Data harga berhasil diperbarui!');
    }

    // Menghapus data harga (Delete Action)
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('harga.index')->with('success', 'Data berhasil dihapus!');
    }

    // =========================================================================
    // 2. LOGIKA PENAWARAN PROYEK (COMBINED)
    // =========================================================================
    // Catatan: Logika ini khusus untuk membuat Offer Tipe Proyek
    // yang menggabungkan Produk & Jasa secara manual.

    public function createCombined(Request $request)
    {
        $token = Str::uuid()->toString();
        $request->session()->put('form_token', $token);
        $products = Product::all();

        // Pastikan view ini ada
        return view('penawaran.create_combined', ['products' => $products]);
    }

    public function storeCombined(Request $request)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
        ]);

        // Hitung total di backend
        $totalJasa = 0;
        if ($request->has('produk')) {
            foreach ($request->produk as $item) {
                $totalJasa += ($item['volume'] ?? 0) * ($item['harga'] ?? 0);
            }
        }

        $diskonGlobal = $request->diskon_global ?? 0;
        $grandTotal = $totalJasa - $diskonGlobal;
        if($grandTotal < 0) $grandTotal = 0;

        DB::beginTransaction(); // Gunakan transaksi database agar aman
        try {
            // 1. Simpan Offer Utama
            $offer = Offer::create([
                'nama_klien'            => $request->nama_klien,
                'client_details'        => $request->client_details,
                'diskon_global'         => $diskonGlobal,
                'total_keseluruhan'     => $grandTotal,
                'jenis_penawaran'       => 'proyek',
                'hilangkan_grand_total' => $request->has('hilangkan_grand_total') ? 1 : 0,
            ]);

            // 2. Simpan Item Detail Jasa
            if ($request->has('produk')) {
                foreach ($request->produk as $itemData) {
                    if (!empty($itemData['nama'])) {
                        $offer->items()->create([
                            'nama_produk'        => $itemData['nama'],      // Menggunakan kolom nama produk untuk Nama Jasa
                            'area_dinding'       => $itemData['kategori'],  // Menggunakan kolom area dinding untuk Kategori Pekerjaan
                            'deskripsi_tambahan' => $itemData['satuan'],    // Menggunakan kolom deskripsi_tambahan untuk Satuan
                            'volume'             => $itemData['volume'] ?? 0,
                            'harga_per_m2'       => $itemData['harga'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();
            // Redirect ke histori
            return redirect()->route('histori.index')->with('success', 'Penawaran Jasa berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}