<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class PublicOfferController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');

        $query = Offer::where('is_public', true)->latest();
        
        if ($type === 'produk') {
            $query->where('jenis_penawaran', 'produk');
        } elseif ($type === 'jasa') {
            $query->where('jenis_penawaran', '!=', 'produk'); // atau 'jasa'
        }

        $offers = $query->paginate(12);

        return view('front.penawaran', compact('offers', 'type'));
    }

    public function show($id)
    {
        // Hanya yang is_public = true yang boleh dilihat
        $offer = Offer::with(['items', 'jasaItems'])
            ->where('is_public', true)
            ->findOrFail($id);

        return view('front.penawaran-detail', compact('offer'));
    }
}
