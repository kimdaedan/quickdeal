@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Buat Purchase Order (PO)</h1>
            <p class="text-sm text-gray-500 mt-1">Penawaran: <span class="font-semibold text-blue-600">{{ $offer->judul_publik ?: 'SP-'.str_pad($offer->id, 4, '0', STR_PAD_LEFT) }}</span></p>
        </div>

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('po.store', $offer->id) }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Name / Business Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name ?? '') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div class="mb-5">
                <label for="detail_project" class="block text-sm font-semibold text-gray-700 mb-1">Detail Project</label>
                <textarea name="detail_project" id="detail_project" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-400"
                          placeholder="Jelaskan secara detail mengenai kebutuhan proyek Anda...">{{ old('detail_project') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Mobile Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email ?? '') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
            
            @if($offer->jenis_penawaran === 'produk')
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">Sesuaikan Kuantitas Produk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-sm font-semibold text-gray-700">Produk</th>
                                <th class="px-3 py-2 text-sm font-semibold text-gray-700 text-right">Harga Satuan</th>
                                <th class="px-3 py-2 text-sm font-semibold text-gray-700 text-center w-24">Qty</th>
                                <th class="px-3 py-2 text-sm font-semibold text-gray-700 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $initialSubtotal = 0; @endphp
                            @foreach($offer->items as $item)
                            @php
                                $harga = $item->harga_per_m2;
                                $diskonNominal = 0;
                                if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                                    $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                                }
                                $hargaBersih = $harga - $diskonNominal;
                                $totalItem = $hargaBersih * $item->volume;
                                $initialSubtotal += $totalItem;
                            @endphp
                            <tr class="border-b border-gray-100 po-item-row" data-price="{{ $hargaBersih }}">
                                <td class="px-3 py-2 text-sm text-gray-800">
                                    {{ $item->nama_produk }}
                                    @if($diskonNominal > 0)
                                        <br><span class="text-xs text-red-500">Diskon/item: Rp {{ number_format($diskonNominal, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-800 text-right">Rp {{ number_format($hargaBersih, 0, ',', '.') }}</td>
                                <td class="px-3 py-2">
                                    <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->volume }}" min="1" required
                                           class="w-full px-2 py-1 text-center border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 qty-input">
                                </td>
                                <td class="px-3 py-2 text-sm font-medium text-gray-900 text-right item-total">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            @if($offer->diskon_global > 0)
                            <tr>
                                <td colspan="3" class="px-3 py-2 text-sm font-semibold text-gray-600 text-right">Subtotal</td>
                                <td class="px-3 py-2 text-sm font-semibold text-gray-900 text-right" id="po-subtotal">Rp {{ number_format($initialSubtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-3 py-2 text-sm font-semibold text-red-600 text-right" data-global-discount="{{ $offer->diskon_global }}">Diskon Global</td>
                                <td class="px-3 py-2 text-sm font-semibold text-red-600 text-right">- Rp {{ number_format($offer->diskon_global, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-3 py-3 text-base font-bold text-gray-900 text-right uppercase">Estimasi Grand Total</td>
                                <td class="px-3 py-3 text-base font-bold text-green-600 text-right" id="po-grand-total">Rp {{ number_format($initialSubtotal - $offer->diskon_global, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="text-xs text-gray-500 mt-2 italic">* Anda dapat menyesuaikan kuantitas produk yang ingin dipesan.</p>
            </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('front.penawaran.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md">Kirim PO</button>
            </div>
        </form>

    </div>
</div>

@if($offer->jenis_penawaran === 'produk')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty-input');
    const globalDiscountEl = document.querySelector('[data-global-discount]');
    const globalDiscount = globalDiscountEl ? parseInt(globalDiscountEl.dataset.globalDiscount) : 0;
    
    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function calculateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.po-item-row').forEach(row => {
            const price = parseInt(row.dataset.price);
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            const rowTotal = price * qty;
            
            row.querySelector('.item-total').textContent = formatRupiah(rowTotal);
            subtotal += rowTotal;
        });

        const subtotalEl = document.getElementById('po-subtotal');
        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);

        let grandTotal = subtotal - globalDiscount;
        if (grandTotal < 0) grandTotal = 0;
        document.getElementById('po-grand-total').textContent = formatRupiah(grandTotal);
    }

    qtyInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
});
</script>
@endif
@endsection
