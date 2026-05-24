<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    /**
     * Show the form for creating a new PO.
     */
    public function create($offer_id)
    {
        $offer = Offer::findOrFail($offer_id);
        
        // Ensure only public offers can be PO'd directly from the public page
        // Or if it's accessed via another way, we just pass the offer.
        return view('po.create', compact('offer'));
    }

    /**
     * Store a newly created PO in storage.
     */
    public function store(Request $request, $offer_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail_project' => 'required|string',
            'alamat_detail' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $offer = Offer::findOrFail($offer_id);
        $offer->load('items');

        $customQuantities = null;
        $customTotal = null;

        if ($offer->jenis_penawaran === 'produk' && $request->has('quantities') && is_array($request->quantities)) {
            $customQuantities = $request->quantities;
            $subtotal = 0;
            
            foreach ($offer->items as $item) {
                if (isset($customQuantities[$item->id])) {
                    $qty = max(1, (int)$customQuantities[$item->id]);
                    $customQuantities[$item->id] = $qty; // Ensure it's stored correctly
                    
                    $harga = $item->harga_per_m2;
                    $diskonNominal = 0;
                    if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                        $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                    }
                    $hargaBersih = $harga - $diskonNominal;
                    
                    $subtotal += ($hargaBersih * $qty);
                } else {
                    // Fallback to default volume if not provided
                    $harga = $item->harga_per_m2;
                    $diskonNominal = 0;
                    if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                        $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                    }
                    $subtotal += (($harga - $diskonNominal) * $item->volume);
                }
            }
            
            $customTotal = max(0, $subtotal - $offer->diskon_global);
        }

        PurchaseOrder::create([
            'offer_id' => $offer->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'detail_project' => $request->detail_project,
            'alamat_detail' => $request->alamat_detail,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'pending',
            'custom_quantities' => $customQuantities,
            'custom_total' => $customTotal,
        ]);

        return redirect()->route('client.dashboard')->with('success', 'Purchase Order berhasil dibuat dan sedang menunggu persetujuan.');
    }

    /**
     * Display PO history for the logged in user (client).
     */
    public function historyUser(Request $request)
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = PurchaseOrder::with(['offer', 'invoice'])->where('user_id', Auth::id());

        // Apply Search (Nama / No Referensi Penawaran)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('detail_project', 'like', '%' . $search . '%')
                  ->orWhereHas('offer', function($oq) use ($search) {
                      $oq->where('judul_publik', 'like', '%' . $search . '%')
                         ->orWhere('id', 'like', '%' . $search . '%');
                  });
                
                // Support searching format "SP-0005"
                if (preg_match('/SP-(\d+)/i', $search, $matches)) {
                    $offerId = (int)$matches[1];
                    $q->orWhere('offer_id', $offerId);
                }
            });
        }

        // Apply Status Filter
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Apply Date Filters
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Order: Pending status first (with oldest pending first), then non-pending newest first
        $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END ASC")
              ->orderByRaw("CASE WHEN status = 'pending' THEN created_at END ASC")
              ->orderByRaw("CASE WHEN status != 'pending' THEN created_at END DESC");

        $pos = $query->paginate(10)->withQueryString();

        return view('po.history', compact('pos'));
    }

    /**
     * Display PO history for admin.
     */
    public function historyAdmin(Request $request)
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = PurchaseOrder::with(['offer', 'user', 'invoice']);

        // Apply Search (Nama / No Referensi Penawaran)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('detail_project', 'like', '%' . $search . '%')
                  ->orWhereHas('offer', function($oq) use ($search) {
                      $oq->where('judul_publik', 'like', '%' . $search . '%')
                         ->orWhere('id', 'like', '%' . $search . '%');
                  });
                
                // Support searching format "SP-0005"
                if (preg_match('/SP-(\d+)/i', $search, $matches)) {
                    $offerId = (int)$matches[1];
                    $q->orWhere('offer_id', $offerId);
                }
            });
        }

        // Apply Status Filter
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Apply Date Filters
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Order: Pending status first (with oldest pending first), then non-pending newest first
        $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END ASC")
              ->orderByRaw("CASE WHEN status = 'pending' THEN created_at END ASC")
              ->orderByRaw("CASE WHEN status != 'pending' THEN created_at END DESC");

        $pos = $query->paginate(20)->withQueryString();

        return view('po.history', compact('pos'));
    }

    /**
     * Update PO status and automatically generate invoice if received.
     */
    public function updateStatus(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        $request->validate([
            'status' => 'required|string|in:pending,receive,rejected'
        ]);

        $po->status = $request->status;
        $po->save();

        if ($request->status === 'receive') {
            $offer = $po->offer;
            if ($offer) {
                // Check if invoice already exists to avoid duplicates
                $existingInvoice = \App\Models\Invoice::where('purchase_order_id', $po->id)->first();
                if (!$existingInvoice) {
                    $total = $po->custom_total ?? $offer->total_keseluruhan;
                    \App\Models\Invoice::create([
                        'offer_id' => $offer->id,
                        'purchase_order_id' => $po->id,
                        'no_invoice' => 'INV-' . date('Ymd') . '-' . $offer->id,
                        'nama_klien' => $po->name,
                        'total_penawaran' => $total,
                        'total_tambahan' => 0,
                        'diskon' => 0,
                        'grand_total' => $total,
                        'total_dp' => 0,
                        'sisa_pembayaran' => $total,
                    ]);
                }
            }
        }

        return back()->with('success', 'Status PO berhasil diperbarui menjadi ' . ucfirst($request->status));
    }

    /**
     * Display the professional print template for a Purchase Order.
     */
    public function print($id)
    {
        $po = PurchaseOrder::with('offer.items', 'offer.jasaItems')->findOrFail($id);
        
        // Ensure client can only print their own POs
        if (Auth::user()->role === 'client' && $po->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke Purchase Order ini.');
        }

        return view('po.print', compact('po'));
    }

    /**
     * Show the form for editing the specified Purchase Order.
     */
    public function edit($id)
    {
        $po = PurchaseOrder::with('offer.items')->findOrFail($id);
        
        // Ensure user is the client who owns the PO
        if (Auth::user()->role === 'client' && $po->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke Purchase Order ini.');
        }

        // Ensure PO is still pending
        if ($po->status !== 'pending') {
            return redirect()->route('client.po.history')->with('error', 'Purchase Order yang sudah diproses tidak dapat diedit.');
        }

        $offer = $po->offer;
        return view('po.edit', compact('po', 'offer'));
    }

    /**
     * Update the specified Purchase Order in storage.
     */
    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        // Ensure user is the client who owns the PO
        if (Auth::user()->role === 'client' && $po->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke Purchase Order ini.');
        }

        // Ensure PO is still pending
        if ($po->status !== 'pending') {
            return redirect()->route('client.po.history')->with('error', 'Purchase Order yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'detail_project' => 'required|string',
            'alamat_detail' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $offer = $po->offer;
        $offer->load('items');

        $customQuantities = null;
        $customTotal = null;

        if ($offer->jenis_penawaran === 'produk' && $request->has('quantities') && is_array($request->quantities)) {
            $customQuantities = $request->quantities;
            $subtotal = 0;
            
            foreach ($offer->items as $item) {
                if (isset($customQuantities[$item->id])) {
                    $qty = max(1, (int)$customQuantities[$item->id]);
                    $customQuantities[$item->id] = $qty; // Ensure it's stored correctly
                    
                    $harga = $item->harga_per_m2;
                    $diskonNominal = 0;
                    if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                        $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                    }
                    $hargaBersih = $harga - $diskonNominal;
                    
                    $subtotal += ($hargaBersih * $qty);
                } else {
                    // Fallback to default volume if not provided
                    $harga = $item->harga_per_m2;
                    $diskonNominal = 0;
                    if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                        $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                    }
                    $subtotal += (($harga - $diskonNominal) * $item->volume);
                }
            }
            
            $customTotal = max(0, $subtotal - $offer->diskon_global);
        }

        $po->update([
            'name' => $request->name,
            'detail_project' => $request->detail_project,
            'alamat_detail' => $request->alamat_detail,
            'phone' => $request->phone,
            'email' => $request->email,
            'custom_quantities' => $customQuantities,
            'custom_total' => $customTotal,
        ]);

        return redirect()->route('client.po.history')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    /**
     * Remove the specified Purchase Order from storage.
     */
    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        // Authorization check
        if (Auth::user()->role === 'client') {
            if ($po->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke Purchase Order ini.');
            }
            // Client can only delete pending POs
            if ($po->status !== 'pending') {
                return back()->with('error', 'Hanya Purchase Order dengan status pending yang dapat dihapus.');
            }
        }

        // Admin can delete any PO

        // Check if there is an associated invoice that was automatically created
        $invoice = \App\Models\Invoice::where('purchase_order_id', $po->id)->first();
        if ($invoice) {
            $invoice->delete();
        }

        $po->delete();

        return back()->with('success', 'Purchase Order berhasil dihapus.');
    }

    /**
     * Explicitly generate an invoice for the given Purchase Order.
     */
    public function createInvoice($id)
    {
        $po = PurchaseOrder::with('offer')->findOrFail($id);

        if ($po->status !== 'receive' && $po->status !== 'approved') {
            return back()->with('error', 'Invoice hanya dapat dibuat untuk Purchase Order yang telah disetujui.');
        }

        $offer = $po->offer;
        if ($offer) {
            $existingInvoice = \App\Models\Invoice::where('purchase_order_id', $po->id)->first();
            if ($existingInvoice) {
                return redirect()->route('invoice.histori')->with('error', 'Invoice untuk Purchase Order ini sudah ada.');
            }

            $total = $po->custom_total ?? $offer->total_keseluruhan;
            $invoice = \App\Models\Invoice::create([
                'offer_id' => $offer->id,
                'purchase_order_id' => $po->id,
                'no_invoice' => 'INV-' . date('Ymd') . '-' . $offer->id,
                'nama_klien' => $po->name,
                'total_penawaran' => $total,
                'total_tambahan' => 0,
                'diskon' => 0,
                'grand_total' => $total,
                'total_dp' => 0,
                'sisa_pembayaran' => $total,
            ]);

            return redirect()->route('invoice.histori')->with('success', 'Invoice berhasil dibuat untuk Purchase Order.');
        }

        return back()->with('error', 'Penawaran referensi untuk PO ini tidak ditemukan.');
    }
}
