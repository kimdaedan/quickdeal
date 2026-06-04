<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Product; // Anda meng-import ini, pastikan modelnya ada jika digunakan

class InvoiceController extends Controller
{
    /**
     * Menampilkan halaman histori dari semua invoice.
     */
    public function index(Request $request)
    {
        // Ambil kata kunci dan filter
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mulai query ke model Invoice
        $query = Invoice::query();

        // Jika user adalah client, hanya tampilkan invoice milik mereka (berdasarkan nama)
        if (auth()->user()->role === 'client') {
            $query->where('nama_klien', auth()->user()->name);
        }

        // Jika ada pencarian, filter berdasarkan nama klien atau no. invoice
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_klien', 'like', '%' . $search . '%')
                  ->orWhere('no_invoice', 'like', '%' . $search . '%');
            });
        }

        // Filter Status
        if ($statusFilter) {
            if ($statusFilter === 'paid') {
                $query->where('status', 'paid');
            } elseif ($statusFilter === 'due') {
                $query->where('status', '!=', 'paid')
                      ->where('created_at', '>=', now()->subMonth());
            } elseif ($statusFilter === 'overdue') {
                $query->where('status', '!=', 'paid')
                      ->where('created_at', '<', now()->subMonth());
            } elseif ($statusFilter === 'pending_verification') {
                $query->whereHas('payments', function($q) {
                    $q->where('status_verifikasi', 'pending');
                });
            }
        }

        // Filter Rentang Tanggal
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Kueri Pengurutan Multi-Prioritas:
        // 1. Ada bukti transfer pending (Menunggu Verifikasi)
        // 2. Jatuh Tempo (Belum lunas & > 1 bulan sejak dibuat)
        // 3. Reguler (Terbaru dibuat)
        $invoices = $query->orderByRaw("CASE 
            WHEN (SELECT COUNT(*) FROM invoice_payments WHERE invoice_payments.invoice_id = invoices.id AND invoice_payments.status_verifikasi = 'pending') > 0 THEN 1 
            WHEN status != 'paid' AND created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 2 
            ELSE 3 
        END ASC")
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Kirim data ke view beserta filter aktif
        return view('invoice.histori', [
            'invoices'     => $invoices,
            'search'       => $search ?? '',
            'statusFilter' => $statusFilter ?? '',
            'startDate'    => $startDate ?? '',
            'endDate'      => $endDate ?? '',
        ]);
    }

    /**
     * Menampilkan form untuk membuat invoice baru (dari nol).
     */
    public function create()
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }
        return view('invoice.create');
    }

    /**
     * Menampilkan form invoice baru, dengan data yang ditarik dari Penawaran.
     */
    public function createFromOffer(Offer $offer)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }
        $offer->load(['items', 'jasaItems']);
        return view('invoice.create_from_offer', [
            'offer' => $offer
        ]);
    }

    /**
     * Menyimpan invoice baru yang dibuat dari penawaran.
     */

    public function print($id)
    {
        $invoice = \App\Models\Invoice::with(['offer', 'additions', 'payments'])->findOrFail($id);
        
        // Pastikan client hanya bisa print invoicenya sendiri
        if (auth()->user()->role === 'client' && $invoice->nama_klien !== auth()->user()->name) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('invoice.print', compact('invoice'));
    }


    public function storeFromOffer(Request $request)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }
        // Validasi data dasar
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
        ]);

        $offer = Offer::find($request->offer_id);

        // --- Kalkulasi Total di Backend ---
        $total_penawaran = $offer->total_keseluruhan;
        $total_tambahan = 0;
        $total_dp = 0;
        $diskon = $request->diskon ?? 0;

        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $item) {
                $total_tambahan += $item['harga'] ?? 0;
            }
        }
        if ($request->has('dp')) {
            foreach ($request->dp as $item) {
                $total_dp += $item['jumlah'] ?? 0;
            }
        }

        $grand_total = ($total_penawaran + $total_tambahan) - $diskon;
        $sisa_pembayaran = $grand_total - $total_dp;
        // --- Akhir Kalkulasi ---

        // 1. Simpan data ke tabel 'invoices'
        $invoice = Invoice::create([
            'offer_id' => $offer->id,
            'no_invoice' => $request->no_invoice ?? 'INV-' . date('Ymd') . '-' . $offer->id,
            'nama_klien' => $offer->nama_klien,
            'total_penawaran' => $total_penawaran,
            'total_tambahan' => $total_tambahan,
            'diskon' => $diskon,
            'grand_total' => $grand_total,
            'total_dp' => $total_dp,
            'sisa_pembayaran' => $sisa_pembayaran,
        ]);

        // 2. Simpan data ke tabel 'invoice_additions'
        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $itemData) {
                if (!empty($itemData['nama'])) {
                    $invoice->additions()->create([
                        'nama_pekerjaan' => $itemData['nama'],
                        'harga' => $itemData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // 3. Simpan data ke tabel 'invoice_payments'
        if ($request->has('dp')) {
            foreach ($request->dp as $itemData) {
                if (!empty($itemData['keterangan'])) {
                    $invoice->payments()->create([
                        'keterangan' => $itemData['keterangan'],
                        'jumlah' => $itemData['jumlah'] ?? 0,
                    ]);
                }
            }
        }

        // Alihkan ke halaman histori invoice dengan pesan sukses
        return redirect()->route('invoice.histori')->with('success', 'Invoice baru berhasil dibuat!');
    }

    /**
     * Menampilkan detail invoice.
     */
    public function show(Invoice $invoice)
    {
        // Pastikan client hanya bisa melihat invoicenya sendiri
        if (auth()->user()->role === 'client' && $invoice->nama_klien !== auth()->user()->name) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        // Load semua relasi yang dibutuhkan untuk 'show.blade.php'
        $invoice->load(['offer.items', 'offer.jasaItems', 'additions', 'payments']);

        return view('invoice.show', compact('invoice'));
    }

    /**
     * Menampilkan form untuk mengedit invoice.
     */
    public function edit(Invoice $invoice)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }
        // Load relasi yang sama untuk form edit
        $invoice->load(['offer.items', 'offer.jasaItems', 'additions', 'payments']);

        // Mengarahkan ke view edit yang baru dibuat
        return view('invoice.edit', compact('invoice'));
    }


    /**
     * Memperbarui data invoice di database.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }
        // Validasi dasar (tambahkan sesuai kebutuhan)
        $request->validate([
            'diskon' => 'nullable|numeric|min:0',
            'pekerjaan.*.nama' => 'nullable|string',
            'pekerjaan.*.harga' => 'nullable|numeric|min:0',
            'dp.*.keterangan' => 'nullable|string',
            'dp.*.jumlah' => 'nullable|numeric|min:0',
        ]);

        // --- Kalkulasi Ulang Total di Backend ---
        $total_penawaran = $invoice->total_penawaran; // Ambil dari data yg ada
        $total_tambahan = 0;
        $total_dp = 0;
        $diskon = $request->diskon ?? 0;

        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $item) {
                $total_tambahan += $item['harga'] ?? 0;
            }
        }
        if ($request->has('dp')) {
            foreach ($request->dp as $item) {
                $total_dp += $item['jumlah'] ?? 0;
            }
        }

        $grand_total = ($total_penawaran + $total_tambahan) - $diskon;
        $sisa_pembayaran = $grand_total - $total_dp;
        // --- Akhir Kalkulasi ---

        // 1. Update data di tabel 'invoices'
        $invoice->update([
            'total_tambahan' => $total_tambahan,
            'diskon' => $diskon,
            'grand_total' => $grand_total,
            'total_dp' => $total_dp,
            'sisa_pembayaran' => $sisa_pembayaran,
        ]);

        // 2. Hapus data lama dan simpan data baru ke 'invoice_additions'
        $invoice->additions()->delete();
        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $itemData) {
                if (!empty($itemData['nama'])) {
                    $invoice->additions()->create([
                        'nama_pekerjaan' => $itemData['nama'],
                        'harga' => $itemData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // 3. Hapus data lama dan simpan data baru ke 'invoice_payments'
        $invoice->payments()->delete();
        if ($request->has('dp')) {
            foreach ($request->dp as $itemData) {
                if (!empty($itemData['keterangan'])) {
                    $invoice->payments()->create([
                        'keterangan' => $itemData['keterangan'],
                        'jumlah' => $itemData['jumlah'] ?? 0,
                    ]);
                }
            }
        }

        // Alihkan ke halaman show invoice dengan pesan sukses
        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Invoice berhasil diperbarui!');
    }

    /**
     * Menghapus data invoice dari database.
     */
    public function destroy(Invoice $invoice)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }
        // Menggunakan Route-Model Binding (Invoice $invoice)
        // untuk otomatis menemukan data invoice berdasarkan ID dari URL.

        $invoice->delete();

        return redirect()->route('invoice.histori')->with('success', 'Invoice berhasil dihapus!');
    }

    /**
     * Menambahkan data payment (cicilan/pelunasan).
     */
    public function addPayment(Request $request, Invoice $invoice)
    {
        $role = auth()->user()->role;
        
        if ($role === 'client') {
            // Client: Keterangan (wajib), Bukti Transfer (wajib), nominal (tidak ada, di-set 0 pending)
            $request->validate([
                'keterangan'     => 'required|string|max:255',
                'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
                'bukti_transfer.image'    => 'Berkas yang diunggah harus berupa gambar.',
                'bukti_transfer.mimes'    => 'Format gambar harus berupa jpeg, png, jpg, atau gif.',
                'bukti_transfer.max'      => 'Ukuran gambar bukti transfer terlalu besar! Maksimal 2MB (2048 KB).',
                'keterangan.required'     => 'Keterangan pembayaran wajib diisi.',
            ]);
            
            if ($invoice->sisa_pembayaran <= 0) {
                return redirect()->back()->with('error', 'Invoice ini sudah lunas.');
            }
            
            // Simpan berkas bukti transfer langsung ke folder public/bukti_transfer
            $buktiTransferPath = null;
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('bukti_transfer'), $filename);
                $buktiTransferPath = 'bukti_transfer/' . $filename;
            }
            
            // Simpan data pembayaran sebagai pending dengan jumlah = 0
            $invoice->payments()->create([
                'keterangan'        => $request->keterangan,
                'jumlah'            => 0,
                'bukti_transfer'    => $buktiTransferPath,
                'status_verifikasi' => 'pending',
            ]);
            
            return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah! Menunggu verifikasi admin.');
            
        } else {
            // Admin: Keterangan (wajib), Jumlah (wajib), Bukti Transfer (opsional), status langsung verified
            $request->validate([
                'keterangan'     => 'required|string|max:255',
                'jumlah'         => 'required|numeric|min:1|max:' . $invoice->sisa_pembayaran,
                'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'bukti_transfer.image' => 'Berkas yang diunggah harus berupa gambar.',
                'bukti_transfer.mimes' => 'Format gambar harus berupa jpeg, png, jpg, atau gif.',
                'bukti_transfer.max'   => 'Ukuran gambar bukti transfer terlalu besar! Maksimal 2MB (2048 KB).',
                'keterangan.required'  => 'Keterangan pembayaran wajib diisi.',
                'jumlah.required'      => 'Jumlah pembayaran wajib diisi.',
                'jumlah.min'           => 'Jumlah pembayaran minimal Rp 1.',
                'jumlah.max'           => 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($invoice->sisa_pembayaran, 0, ',', '.') . ').',
            ]);
            
            if ($invoice->sisa_pembayaran <= 0) {
                return redirect()->back()->with('error', 'Invoice ini sudah lunas.');
            }
            
            // Simpan berkas bukti transfer (jika ada) langsung ke folder public/bukti_transfer
            $buktiTransferPath = null;
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('bukti_transfer'), $filename);
                $buktiTransferPath = 'bukti_transfer/' . $filename;
            }
            
            // Simpan data pembayaran langsung terverifikasi
            $invoice->payments()->create([
                'keterangan'        => $request->keterangan,
                'jumlah'            => $request->jumlah,
                'bukti_transfer'    => $buktiTransferPath,
                'status_verifikasi' => 'verified',
            ]);
            
            // Kalkulasi ulang total DP yang terverifikasi
            $total_dp = $invoice->payments()->where('status_verifikasi', 'verified')->sum('jumlah');
            
            // Kalkulasi ulang sisa pembayaran
            $sisa_pembayaran = $invoice->grand_total - $total_dp;
            if ($sisa_pembayaran < 0) {
                $sisa_pembayaran = 0;
            }
            
            // Tentukan status
            $status = ($sisa_pembayaran <= 0) ? 'paid' : 'due';
            
            // Update record invoice utama
            $invoice->update([
                'total_dp'        => $total_dp,
                'sisa_pembayaran' => $sisa_pembayaran,
                'status'          => $status
            ]);
            
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan oleh Admin!');
        }
    }

    /**
     * Memverifikasi pembayaran pending oleh Admin.
     */
    public function verifyPayment(Request $request, $id)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }

        $payment = \App\Models\InvoicePayment::findOrFail($id);
        $invoice = $payment->invoice;

        if ($payment->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diverifikasi sebelumnya.');
        }

        $request->validate([
            'jumlah' => 'required|numeric|min:1|max:' . $invoice->sisa_pembayaran,
        ]);

        // Update data pembayaran
        $payment->update([
            'jumlah'            => $request->jumlah,
            'status_verifikasi' => 'verified',
        ]);

        // Kalkulasi ulang total DP yang terverifikasi
        $total_dp = $invoice->payments()->where('status_verifikasi', 'verified')->sum('jumlah');

        // Kalkulasi ulang sisa pembayaran
        $sisa_pembayaran = $invoice->grand_total - $total_dp;
        if ($sisa_pembayaran < 0) {
            $sisa_pembayaran = 0;
        }

        // Tentukan status
        $status = ($sisa_pembayaran <= 0) ? 'paid' : 'due';

        // Update record invoice utama
        $invoice->update([
            'total_dp'        => $total_dp,
            'sisa_pembayaran' => $sisa_pembayaran,
            'status'          => $status
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi dan disetujui!');
    }

    /**
     * Menolak pembayaran pending oleh Admin.
     */
    public function rejectPayment($id)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Anda tidak memiliki akses ke tindakan ini.');
        }

        $payment = \App\Models\InvoicePayment::findOrFail($id);

        if ($payment->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pembayaran pending yang dapat ditolak.');
        }

        // Hapus file bukti transfer jika ada
        if ($payment->bukti_transfer) {
            if (str_starts_with($payment->bukti_transfer, 'bukti_transfer/')) {
                $filePath = public_path($payment->bukti_transfer);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } else {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($payment->bukti_transfer);
            }
        }

        // Hapus data pembayaran dari database
        $payment->delete();

        return redirect()->back()->with('success', 'Pembayaran berhasil ditolak dan dihapus!');
    }
}
