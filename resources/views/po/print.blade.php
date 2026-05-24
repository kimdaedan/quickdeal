<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $po->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: A4; margin: 0; }
            body { margin: 0; padding: 0; background-color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            #main-container { width: 210mm; min-height: 297mm; margin: 0 auto !important; padding: 20mm !important; box-shadow: none !important; border: none !important; }
            table { page-break-inside: auto; width: 100%; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
        body { background-color: #f3f4f6; }
        #main-container { background-color: white; width: 210mm; min-height: 297mm; margin: 20px auto; padding: 20mm; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .sans { font-family: Arial, Helvetica, sans-serif; }
        .nav-floating { position: fixed; top: 20px; right: 20px; z-index: 100; display: flex; gap: 10px; }
    </style>
</head>
<body class="bg-gray-100 text-black">

    <div class="nav-floating no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition">
            <span>🖨️</span> Cetak PO
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg transition">
            Tutup
        </button>
    </div>

    <div id="main-container" class="sans max-w-[21cm] mx-auto bg-white shadow-xl my-10 p-10 print:shadow-none print:my-0 relative">
        
        <!-- Header -->
        <header class="w-full mb-8 pb-4 border-b-2 border-gray-800 flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-extrabold tracking-wider text-gray-900 uppercase">Purchase Order</h1>
                <p class="text-sm text-gray-500 mt-1">NO: PO-{{ $po->created_at->format('Ymd') }}-{{ str_pad($po->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-lg uppercase">{{ $po->name }}</p>
                <p class="text-sm text-gray-700">{{ $po->email }}</p>
                <p class="text-sm text-gray-700">{{ $po->phone }}</p>
            </div>
        </header>

        <!-- To and Date Section -->
        <section class="mt-8 flex justify-between text-sm">
            <div class="w-1/2">
                <p class="text-gray-500 font-semibold mb-1 uppercase tracking-wider">Kepada (Vendor):</p>
                <p class="font-bold text-lg">CV. DAEDAN ENTERPRISE</p>
                <p class="text-gray-700">Perumahan Cipta Asri Blok Cempaka No 128</p>
                <p class="text-gray-700">Tembesi - Sagulung - Batam</p>
                <p class="text-gray-700">Telp: 0812-7013-0979</p>
            </div>
            <div class="w-1/2 text-right">
                <table class="ml-auto text-sm">
                    <tr>
                        <td class="font-semibold text-gray-600 py-1 pr-4 text-left">Tanggal PO</td>
                        <td class="py-1 text-left">: {{ $po->created_at->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold text-gray-600 py-1 pr-4 text-left">Ref. Penawaran</td>
                        <td class="py-1 text-left">: {{ $po->offer ? ($po->offer->judul_publik ?: 'SP-'.str_pad($po->offer->id, 4, '0', STR_PAD_LEFT)) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold text-gray-600 py-1 pr-4 text-left">Status</td>
                        <td class="py-1 text-left uppercase font-bold">: {{ $po->status }}</td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- Project Notes -->
        <section class="mt-8 text-sm">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <p class="font-semibold text-gray-700 mb-2 uppercase text-xs tracking-wider">Catatan / Detail Project:</p>
                <p class="text-gray-800 whitespace-pre-line">{{ $po->detail_project }}</p>
            </div>
        </section>

        <!-- Items Table -->
        <section class="mt-8 text-sm">
            <p class="font-semibold text-gray-700 mb-3 uppercase text-xs tracking-wider">Rincian Pesanan:</p>
            
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 p-2 text-center w-12">No.</th>
                        <th class="border border-gray-300 p-2 text-left">Deskripsi Barang / Jasa</th>
                        @if($po->offer && $po->offer->jenis_penawaran === 'produk')
                            <th class="border border-gray-300 p-2 text-center w-24">Qty</th>
                            <th class="border border-gray-300 p-2 text-right w-32">Harga Satuan</th>
                            <th class="border border-gray-300 p-2 text-right w-36">Total</th>
                        @else
                            <th class="border border-gray-300 p-2 text-right w-36">Total Estimasi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if($po->offer && $po->offer->jenis_penawaran === 'produk')
                        @php $i = 1; @endphp
                        @foreach($po->offer->items as $item)
                            @if(isset($po->custom_quantities[$item->id]) || empty($po->custom_quantities))
                                @php
                                    $qty = isset($po->custom_quantities[$item->id]) ? $po->custom_quantities[$item->id] : $item->volume;
                                    
                                    $harga = $item->harga_per_m2;
                                    $diskonNominal = 0;
                                    if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                                        $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                                    }
                                    $hargaBersih = $harga - $diskonNominal;
                                    $totalBaris = $hargaBersih * $qty;
                                @endphp
                                <tr>
                                    <td class="border border-gray-300 p-2 text-center">{{ $i++ }}</td>
                                    <td class="border border-gray-300 p-2">
                                        <span class="font-medium">{{ $item->nama_produk }}</span>
                                        @if($diskonNominal > 0)
                                            <br><span class="text-xs text-gray-500">Termasuk diskon: Rp {{ number_format($diskonNominal, 0, ',', '.') }}/item</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $qty }}</td>
                                    <td class="border border-gray-300 p-2 text-right">Rp {{ number_format($hargaBersih, 0, ',', '.') }}</td>
                                    <td class="border border-gray-300 p-2 text-right">Rp {{ number_format($totalBaris, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                        
                        <!-- Totals -->
                        @if($po->offer->diskon_global > 0)
                        <tr>
                            <td colspan="4" class="border border-gray-300 p-2 text-right font-medium">Subtotal</td>
                            <td class="border border-gray-300 p-2 text-right">Rp {{ number_format(($po->custom_total ?? 0) + $po->offer->diskon_global, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border border-gray-300 p-2 text-right font-medium text-red-600">Diskon Global</td>
                            <td class="border border-gray-300 p-2 text-right text-red-600">- Rp {{ number_format($po->offer->diskon_global, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="bg-gray-50 font-bold">
                            <td colspan="4" class="border border-gray-300 p-3 text-right uppercase">Total Pesanan</td>
                            <td class="border border-gray-300 p-3 text-right text-lg">Rp {{ number_format($po->custom_total ?? $po->offer->total_keseluruhan, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <!-- Jasa / Service Offer -->
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">1</td>
                            <td class="border border-gray-300 p-2">
                                <span class="font-medium">Pekerjaan Jasa Sesuai Penawaran</span>
                                <br><span class="text-xs text-gray-500">Referensi: {{ $po->offer ? ($po->offer->judul_publik ?: 'SP-'.str_pad($po->offer->id, 4, '0', STR_PAD_LEFT)) : '-' }}</span>
                            </td>
                            <td class="border border-gray-300 p-2 text-right font-bold text-lg">Rp {{ number_format($po->offer ? $po->offer->total_keseluruhan : 0, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>

        <!-- Terms and Signatures -->
        <section class="mt-16 flex justify-between text-sm">
            <div class="w-1/2 pr-8">
                <p class="font-bold mb-2">Syarat & Ketentuan:</p>
                <ol class="list-decimal list-inside text-gray-600 text-xs space-y-1">
                    <li>Purchase Order ini sah sebagai dasar pengerjaan/pengiriman.</li>
                    <li>Harga dan jumlah yang tercantum sesuai dengan kesepakatan bersama.</li>
                    <li>Pembayaran dilakukan sesuai dengan term yang telah disepakati pada penawaran.</li>
                </ol>
            </div>
            
            <div class="w-1/2 text-right">
                <p class="text-gray-600 mb-16">Dikeluarkan Oleh,</p>
                
                <p class="font-bold text-gray-900 border-b border-gray-800 inline-block px-8 pb-1 mb-1">
                    {{ $po->name }}
                </p>
                <p class="text-xs text-gray-500 uppercase tracking-widest">Authorized Signature</p>
            </div>
        </section>

    </div>
</body>
</html>
