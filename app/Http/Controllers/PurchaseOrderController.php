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
    public function historyUser()
    {
        $pos = PurchaseOrder::with('offer')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('po.history', compact('pos'));
    }

    /**
     * Display PO history for admin.
     */
    public function historyAdmin()
    {
        $pos = PurchaseOrder::with(['offer', 'user'])->orderBy('created_at', 'desc')->paginate(20);
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
                $existingInvoice = \App\Models\Invoice::where('offer_id', $offer->id)->first();
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
}
