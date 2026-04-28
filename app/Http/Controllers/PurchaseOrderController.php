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

        PurchaseOrder::create([
            'offer_id' => $offer->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'detail_project' => $request->detail_project,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'pending',
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
                    \App\Models\Invoice::create([
                        'offer_id' => $offer->id,
                        'no_invoice' => 'INV-' . date('Ymd') . '-' . $offer->id,
                        'nama_klien' => $po->name,
                        'total_penawaran' => $offer->total_keseluruhan,
                        'total_tambahan' => 0,
                        'diskon' => 0,
                        'grand_total' => $offer->total_keseluruhan,
                        'total_dp' => 0,
                        'sisa_pembayaran' => $offer->total_keseluruhan,
                    ]);
                }
            }
        }

        return back()->with('success', 'Status PO berhasil diperbarui menjadi ' . ucfirst($request->status));
    }
}
