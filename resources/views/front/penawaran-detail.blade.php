<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penawaran Publik - Quick.Deal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f4f6; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="text-slate-900 pb-20">

    {{-- Navbar Publik --}}
    <nav class="fixed w-full z-50 glass-effect border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
           <a href="{{ route('front.penawaran.index') }}" class="flex items-center gap-3 text-slate-600 hover:text-blue-600 transition font-medium">
                &larr; Kembali ke Daftar
            </a>
            <span class="text-lg font-bold tracking-tight">Quick<span class="text-blue-600">.Deal</span></span>
        </div>
    </nav>

    <div class="pt-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-xl rounded-2xl" id="surat-penawaran">
            
            <div class="mb-4 bg-fuchsia-50 border-l-4 border-fuchsia-400 p-4 rounded text-fuchsia-800 text-sm">
                <strong>Dokumen Publik:</strong> Informasi klien telah disamarkan untuk menjaga privasi.
            </div>

            @if($offer->judul_publik)
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-slate-800 uppercase tracking-widest">{{ $offer->judul_publik }}</h2>
                <div class="w-16 h-1 bg-fuchsia-500 mx-auto mt-2 rounded"></div>
            </div>
            @endif

            {{-- HEADER KOP SURAT --}}
            <header class="w-full mb-6">
                <div class="w-full">
                    <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="w-full h-auto">
                </div>
                <div class="w-full border-b-[4px] border-[#d32f2f] mt-1"></div>
            </header>

            <section class="mb-6 text-sm sans">
                @php
                $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
                $romawi = $bulanRomawi[$offer->created_at->format('n')];
                $tahun = $offer->created_at->format('Y');
                @endphp
                <p>Nomor : 00{{ $offer->id }}/SP/TGI-1/{{ $romawi }}/{{ $tahun }}</p>
                <p>Batam, {{ $offer->created_at->format('d F Y') }}</p>
            </section>

            <section class="mt-8">
                <p class="text-gray-600">Kepada Yth,</p>
                {{-- NAMA KLIEN DISAMARKAN --}}
                <h3 class="text-md font-bold text-gray-800">Bapak/Ibu ********</h3>
                <p class="text-sm text-gray-700 italic text-gray-400">[Detail Rahasia]</p>
                <p class="text-gray-700 mt-2">Dengan Hormat,</p>
            </section>

            <section class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed">
                <p>Kami CV. DAEDAN ENTERPRISE adalah Perusahaan penyedia Jasa dan Produk, didirikan pada tanggal 4 April 2026, website <a href="https://suratpenawaran.biz.id" class="text-blue-600 underline">https://suratpenawaran.biz.id</a>.</p>
                <div>
                    <p>Kami CV. DAEDAN ENTERPRISE begerak di bidang Painting Dan Pekerjaan Sipil lainnya :</p>
                    <ol class="list-decimal list-inside ml-4">
                        <li>Pekerjaan pengecatan dan perawatan gedung</li>
                        <li>Pemasangan partisi dan plafon Finising gypsum dan plafon sunda Plafon</li>
                        <li>Pekerjaan Sipil Rumah Tangga</li>
                    </ol>
                </div>
                <p>Dengan ini kami sampaikan penawaran Upah Jasa :</p>
            </section>

            {{-- PHP LOGIC --}}
            @php
            $showTotal = !$offer->hilangkan_grand_total;
            $groupedItems = $offer->items->groupBy('area_dinding');
            $totalJasa = $offer->jasaItems->sum('harga_jasa');
            @endphp

            <section class="mt-8">
                <div class="w-full overflow-x-auto">
                    @foreach($groupedItems as $kategori => $items)
                    <div class="mb-8">
                        <h4 class="font-bold text-gray-800 mb-2 uppercase border-b-2 border-gray-800 inline-block text-sm">{{ $kategori ?: 'Kategori Pekerjaan' }}</h4>
                        <table class="w-full text-left border-collapse mb-4">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs">Nama Jasa / Produk</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-right">Volume</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-center">Satuan</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-right">Harga Satuan</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotalKategori = 0; @endphp
                                @foreach($items as $item)
                                @php
                                    $totalItem = $item->volume * $item->harga_per_m2;
                                    $subtotalKategori += $totalItem;
                                    $p = \App\Models\Product::where('nama_jasa', $item->nama_produk)->first();
                                    $satuan = $item->deskripsi_tambahan ?: ($p->satuan ?? '-');
                                @endphp
                                <tr class="border-b border-gray-200">
                                    <td class="py-1 px-2 text-xs text-gray-700">{{ $item->nama_produk }}</td>
                                    <td class="py-1 px-2 text-xs text-gray-700 text-right">{{ $item->volume + 0 }}</td>
                                    <td class="py-1 px-2 text-xs text-gray-700 text-center">{{ $satuan }}</td>
                                    <td class="py-1 px-2 text-xs text-gray-700 text-right">Rp {{ number_format($item->harga_per_m2, 0, ',', '.') }}</td>
                                    <td class="py-1 px-2 text-xs text-gray-700 text-right font-medium">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if($showTotal)
                            <tfoot>
                                <tr class="bg-gray-50 font-bold text-gray-800">
                                    <td colspan="4" class="py-2 px-2 text-xs text-right uppercase">Subtotal</td>
                                    <td class="py-2 px-2 text-xs text-right">Rp {{ number_format($subtotalKategori, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    @endforeach

                    @if($offer->jasaItems->isNotEmpty())
                    <div class="mt-4">
                        <h4 class="font-bold text-gray-800 mb-2 uppercase border-b-2 border-gray-800 inline-block text-sm">Pengerjaan Tambahan</h4>
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs" colspan="2">Deskripsi Pengerjaan</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-right">Vol/Sat</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-right">Harga Satuan</th>
                                    <th class="py-2 px-2 font-semibold uppercase text-xs text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offer->jasaItems as $jasa)
                                <tr class="border-b border-gray-200">
                                    <td class="py-1 px-2 text-xs" colspan="2">{{ $jasa->nama_jasa }}</td>
                                    <td class="py-1 px-2 text-xs text-right">{{ $jasa->volume + 0 }} {{ $jasa->satuan }}</td>
                                    <td class="py-1 px-2 text-xs text-right">Rp {{ number_format($jasa->harga_satuan ?? ($jasa->harga_jasa / ($jasa->volume ?: 1)), 0, ',', '.') }}</td>
                                    <td class="py-1 px-2 text-xs text-right font-medium">Rp {{ number_format($jasa->harga_jasa, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if($showTotal)
                            <tfoot>
                                <tr class="bg-gray-50 font-bold text-gray-800">
                                    <td colspan="4" class="py-2 px-2 text-xs text-right uppercase">Total Pengerjaan Tambahan</td>
                                    <td class="py-2 px-2 text-xs text-right">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    @endif

                </div>
            </section>

            @if($showTotal)
            <section class="mt-6 flex flex-col items-end gap-2" id="grand-total-block">
                @if($offer->diskon_global > 0)
                <div class="w-full md:w-1/2 bg-red-50 text-red-700 p-3 border border-red-100 rounded-lg flex justify-between items-center">
                    <span class="text-sm font-bold uppercase">Diskon Global</span>
                    <span class="font-bold">- Rp {{ number_format($offer->diskon_global, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="w-full md:w-1/2">
                    <div class="flex justify-between items-center bg-gray-800 text-white p-4 rounded-xl">
                        <span class="text-sm font-bold uppercase tracking-wider">Grand Total</span>
                        <span class="text-xl font-bold text-green-400">Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </section>
            @endif

            <section class="mt-12 text-sm text-gray-700 leading-relaxed bg-slate-50 p-6 rounded-xl border border-slate-100">
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Teknis Pengerjaan
                </h4>
                <ul class="list-disc list-inside space-y-1 text-slate-600">
                    <li>Semua peralatan pekerjaan akan disiapkan oleh pihak CV. DAEDAN ENTERPRISE</li>
                    <li>Meliputi : Cat, rol, kuas, dempul, plamir, scaffolding dll.</li>
                    <li>Air dan Listrik serta gudang penyimpanan disediakan oleh pemberi kerja.</li>
                    <li>Pengukuran final luas area akan dihitung bersama dan dijadikan patokan untuk nilai pekerjaan yang disepakati nantinya.</li>
                    <li>Semen dan Pasir disediakan oleh pihak CV. DAEDAN ENTERPRISE</li>
                    <li>Pengecekan Test Uji Coba.</li>
                    <li>Finish.</li>
                </ul>
            </section>

            <section class="mt-12 flex justify-end">
                <div class="text-center">
                    <p class="text-slate-600 text-sm mb-2">Hormat kami,</p>
                    <div class="h-24 w-48 relative mx-auto mb-2">
                        <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-full object-contain mx-auto">
                    </div>
                    <p class="font-bold text-gray-900 border-b border-gray-300 inline-block px-4 pb-1">Didan Sirodjuddin</p>
                    <p class="text-gray-500 text-xs mt-1">President Director</p>
                </div>
            </section>

        </div>

        {{-- BUAT PO BUTTON --}}
        <div class="max-w-4xl mx-auto mt-8 flex justify-center">
            <a href="{{ route('po.create', $offer->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transform transition duration-200 hover:-translate-y-1">
                Buat Purchase Order (PO)
            </a>
        </div>
    </div>

</body>
</html>
