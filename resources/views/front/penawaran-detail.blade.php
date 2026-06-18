<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penawaran Publik - Quick.Deal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        .glass-effect { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); }
    </style>
</head>
<body class="text-slate-900 pb-24">

    {{-- Navbar Publik --}}
    <nav class="fixed w-full z-50 glass-effect border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
           <a href="{{ route('front.penawaran.index') }}" class="flex items-center gap-2 text-slate-600 hover:text-indigo-600 transition font-bold text-sm">
                &larr; Kembali ke Daftar
            </a>
            <div class="flex items-center gap-4">
                <a href="https://wa.me/6281393044942?text=Halo%20Admin%20Quick.Deal,%20saya%20ingin%2520bertanya%2520mengenai%2520surat%2520penawaran%2520dengan%2520nomor%252000{{ $offer->id }}..." 
                   target="_blank" 
                   class="text-emerald-600 hover:text-emerald-700 font-bold transition text-xs flex items-center gap-1">
                    Hubungi Admin (WA)
                </a>
                <span class="text-base font-extrabold tracking-tight">Quick<span class="text-indigo-600">.Deal</span></span>
            </div>
        </div>
    </nav>

    <div class="pt-24 px-4 sm:px-6 lg:px-8">
        
        {{-- Success Negotiation alert --}}
        @if(session('success_negotiation'))
        <div class="max-w-4xl mx-auto mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-800 text-sm shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-lg">✅</span>
                <span class="font-medium">{{ session('success_negotiation') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-lg">&times;</button>
        </div>
        @endif

        <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-xl rounded-3xl border border-slate-100 keep-light" id="surat-penawaran">
            
            <div class="mb-6 bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-xl text-indigo-900 text-xs">
                <strong>💡 Informasi Publik:</strong> Identitas klien telah disamarkan demi menjaga privasi & kerahasiaan data penawaran.
            </div>

            @if($offer->judul_publik)
            <div class="text-center mb-8">
                <h2 class="text-xl font-extrabold text-slate-800 uppercase tracking-widest leading-snug">{{ $offer->judul_publik }}</h2>
                <div class="w-12 h-1 bg-indigo-500 mx-auto mt-2.5 rounded-full"></div>
            </div>
            @endif

            {{-- HEADER KOP SURAT --}}
            <header class="w-full mb-8">
                <div class="w-full">
                    <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="w-full h-auto rounded-lg">
                </div>
                <div class="w-full border-b-[4px] border-[#d32f2f] mt-1.5"></div>
            </header>

            <section class="mb-6 text-xs text-slate-500 space-y-1">
                @php
                $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
                $romawi = $bulanRomawi[$offer->created_at->format('n')];
                $tahun = $offer->created_at->format('Y');
                @endphp
                <p class="font-semibold text-slate-700">Nomor : 00{{ $offer->id }}/SP/TGI-1/{{ $romawi }}/{{ $tahun }}</p>
                <p>Batam, {{ $offer->created_at->format('d F Y') }}</p>
            </section>

            <section class="mt-8 text-sm">
                <p class="text-slate-400">Kepada Yth,</p>
                <h3 class="text-base font-bold text-slate-850 uppercase mt-0.5">Bapak/Ibu ********</h3>
                <p class="text-xs text-slate-400 italic mt-0.5">[Detail Informasi Dirahasiakan]</p>
                <p class="text-slate-750 mt-4 leading-relaxed">Dengan Hormat,</p>
            </section>

            @if($offer->jenis_penawaran === 'produk')
            <section class="mt-4 text-sm text-slate-700 leading-relaxed">
                <p>Bersama surat ini, kami <strong>CV. DAEDAN ENTERPRISE</strong> mengajukan penawaran harga untuk produk supply kami dengan rincian sebagai berikut:</p>
            </section>
            @else
            <section class="mt-4 space-y-4 text-sm text-slate-700 leading-relaxed">
                <p>Kami CV. DAEDAN ENTERPRISE adalah Perusahaan penyedia Jasa dan Produk, didirikan pada tanggal 4 April 2026, website <a href="https://suratpenawaran.biz.id" class="text-indigo-600 underline">https://suratpenawaran.biz.id</a>.</p>
                <div>
                    <p>Kami CV. DAEDAN ENTERPRISE begerak di bidang Painting Dan Pekerjaan Sipil lainnya :</p>
                    <ol class="list-decimal list-inside ml-4 space-y-0.5 mt-1 text-slate-650">
                        <li>Pekerjaan pengecatan dan perawatan gedung</li>
                        <li>Pemasangan partisi dan plafon Finising gypsum dan plafon sunda Plafon</li>
                        <li>Pekerjaan Sipil Rumah Tangga</li>
                    </ol>
                </div>
                <p>Dengan ini kami sampaikan penawaran Upah Jasa :</p>
            </section>
            @endif

            {{-- PHP LOGIC --}}
            @php
            $showTotal = !$offer->hilangkan_grand_total;
            $groupedItems = $offer->items->groupBy('area_dinding');
            $totalJasa = $offer->jasaItems->sum('harga_jasa');
            @endphp

            <section class="mt-8">
                <div class="w-full overflow-x-auto rounded-xl border border-slate-200">
                    @if($offer->jenis_penawaran === 'produk')
                        {{-- TABEL PRODUK --}}
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-900 text-white uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="py-3 px-3 text-center w-[5%]">No</th>
                                    <th class="py-3 px-3 w-[25%]">Nama Produk</th>
                                    <th class="py-3 px-3 w-[20%]">Keterangan</th>
                                    <th class="py-3 px-3 w-[15%] text-right">Harga Satuan</th>
                                    <th class="py-3 px-3 w-[5%] text-center">Qty</th>
                                    <th class="py-3 px-3 w-[12%] text-right">Diskon</th>
                                    <th class="py-3 px-3 w-[18%] text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-150">
                                @php $subtotalSemua = 0; @endphp
                                @foreach($offer->items as $index => $item)
                                    @php
                                        $harga = $item->harga_per_m2;
                                        $qty = $item->volume;
                                        $totalBaris = $harga * $qty;
                                        $diskonNominal = 0;
                                        $keterangan = $item->deskripsi_tambahan;

                                        if (preg_match('/(Potongan|Diskon\/Item): Rp ([0-9,.]+)/', $item->deskripsi_tambahan, $matches)) {
                                            $diskonNominal = (int) str_replace(['.', ','], '', $matches[2]);
                                            $totalBaris -= $diskonNominal;
                                            $keterangan = preg_replace('/ \| Potongan: Rp [0-9,.]+/', '', $keterangan);
                                            $keterangan = preg_replace('/Potongan: Rp [0-9,.]+/', '', $keterangan);
                                            $keterangan = preg_replace('/Diskon\/Item: Rp [0-9,.]+/', '', $keterangan);
                                        }

                                        $subtotalSemua += $totalBaris;
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-2.5 px-3 text-center text-slate-500">{{ $index + 1 }}</td>
                                        <td class="py-2.5 px-3 font-bold text-slate-800 leading-tight">
                                            {{ $item->nama_produk }}
                                        </td>
                                        <td class="py-2.5 px-3 text-slate-500 leading-tight">
                                            {!! nl2br(e($keterangan ?: '-')) !!}
                                        </td>
                                        <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 px-3 text-center font-medium">
                                            {{ $qty }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right text-red-650 font-medium whitespace-nowrap">
                                            {{ $diskonNominal > 0 ? '- Rp ' . number_format($diskonNominal, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-extrabold text-slate-900 whitespace-nowrap">
                                            Rp {{ number_format($totalBaris, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if($showTotal)
                                <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                                    @if($offer->diskon_global > 0)
                                        <tr>
                                            <td colspan="6" class="py-3 px-3 text-right text-slate-500 uppercase text-[10px] tracking-wider">Subtotal</td>
                                            <td class="py-3 px-3 text-right whitespace-nowrap text-slate-800 font-bold">
                                                Rp {{ number_format($subtotalSemua, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="bg-slate-100 text-slate-850">
                                        <td colspan="6" class="py-3.5 px-3 text-right uppercase text-[10px] tracking-wider">Grand Total</td>
                                        <td class="py-3.5 px-3 text-right font-extrabold text-sm text-indigo-650 whitespace-nowrap">
                                            Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    @else
                        {{-- TABEL JASA --}}
                        <div class="divide-y divide-slate-200">
                            @foreach($groupedItems as $kategori => $items)
                            <div class="p-4 bg-slate-50/50">
                                <h4 class="font-extrabold text-indigo-650 mb-3 uppercase tracking-wider text-[11px] border-b-2 border-indigo-200 pb-1 inline-block">{{ $kategori ?: 'Kategori Pekerjaan' }}</h4>
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                                            <th class="py-2 px-1">Nama Jasa / Produk</th>
                                            <th class="py-2 px-1 text-right w-[15%]">Volume</th>
                                            <th class="py-2 px-1 text-center w-[12%]">Satuan</th>
                                            <th class="py-2 px-1 text-right w-[18%]">Harga Satuan</th>
                                            <th class="py-2 px-1 text-right w-[20%]">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-150">
                                        @php $subtotalKategori = 0; @endphp
                                        @foreach($items as $item)
                                        @php
                                            $totalItem = $item->volume * $item->harga_per_m2;
                                            $subtotalKategori += $totalItem;
                                            $p = \App\Models\Product::where('nama_jasa', $item->nama_produk)->first();
                                            $satuan = $item->deskripsi_tambahan ?: ($p->satuan ?? '-');
                                        @endphp
                                        <tr class="hover:bg-slate-100/50 transition-colors">
                                            <td class="py-2 px-1 text-xs text-slate-800 font-semibold leading-tight">{{ $item->nama_produk }}</td>
                                            <td class="py-2 px-1 text-xs text-slate-700 text-right">{{ $item->volume + 0 }}</td>
                                            <td class="py-2 px-1 text-xs text-slate-500 text-center">{{ $satuan }}</td>
                                            <td class="py-2 px-1 text-xs text-slate-700 text-right">Rp {{ number_format($item->harga_per_m2, 0, ',', '.') }}</td>
                                            <td class="py-2 px-1 text-xs text-slate-900 text-right font-extrabold">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @if($showTotal)
                                    <tfoot>
                                        <tr class="font-bold text-slate-800 border-t border-slate-200">
                                            <td colspan="4" class="py-2 px-1 text-[10px] text-right uppercase tracking-wider text-slate-400">Subtotal</td>
                                            <td class="py-2 px-1 text-xs text-right text-slate-900 font-bold">Rp {{ number_format($subtotalKategori, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                            @endforeach
                        </div>

                        @if($offer->jasaItems->isNotEmpty())
                        <div class="p-4 border-t border-slate-200">
                            <h4 class="font-extrabold text-indigo-650 mb-3 uppercase tracking-wider text-[11px] border-b-2 border-indigo-200 pb-1 inline-block">Pengerjaan Tambahan</h4>
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                                        <th class="py-2 px-1" colspan="2">Deskripsi Pengerjaan</th>
                                        <th class="py-2 px-1 text-right w-[15%]">Vol/Sat</th>
                                        <th class="py-2 px-1 text-right w-[18%]">Harga Satuan</th>
                                        <th class="py-2 px-1 text-right w-[20%]">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150">
                                    @foreach($offer->jasaItems as $jasa)
                                    <tr class="hover:bg-slate-100/50 transition-colors">
                                        <td class="py-2 px-1 text-xs text-slate-800 font-semibold" colspan="2">{{ $jasa->nama_jasa }}</td>
                                        <td class="py-2 px-1 text-xs text-slate-700 text-right">{{ $jasa->volume + 0 }} {{ $jasa->satuan }}</td>
                                        <td class="py-2 px-1 text-xs text-slate-700 text-right">Rp {{ number_format($jasa->harga_satuan ?? ($jasa->harga_jasa / ($jasa->volume ?: 1)), 0, ',', '.') }}</td>
                                        <td class="py-2 px-1 text-xs text-slate-900 text-right font-extrabold">Rp {{ number_format($jasa->harga_jasa, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($showTotal)
                                <tfoot>
                                    <tr class="font-bold text-slate-800 border-t border-slate-200">
                                        <td colspan="4" class="py-2 px-1 text-[10px] text-right uppercase tracking-wider text-slate-400">Total Tambahan</td>
                                        <td class="py-2 px-1 text-xs text-right text-slate-900 font-bold">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                        @endif
                    @endif
                </div>
            </section>

            @if($showTotal && $offer->jenis_penawaran !== 'produk')
            <section class="mt-6 flex flex-col items-end gap-2" id="grand-total-block">
                @if($offer->diskon_global > 0)
                <div class="w-full md:w-1/2 bg-red-50 text-red-700 p-3 border border-red-100 rounded-2xl flex justify-between items-center text-xs">
                    <span class="font-bold uppercase tracking-wider">Diskon Global</span>
                    <span class="font-bold text-sm">- Rp {{ number_format($offer->diskon_global, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="w-full md:w-1/2">
                    <div class="flex justify-between items-center bg-slate-900 text-white p-4 rounded-2xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Grand Total</span>
                        <span class="text-lg font-extrabold text-indigo-400">Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </section>
            @endif

            @if($offer->jenis_penawaran === 'produk')
            <section class="mt-12 text-xs text-slate-600 leading-relaxed bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
                    ℹ️ Keterangan Penting
                </h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Harga sudah termasuk Pajak Pertambahan Nilai (PPN).</li>
                    <li>Barang yang sudah diserahterimakan / dibeli tidak dapat dibatalkan atau dikembalikan secara sepihak.</li>
                    <li>Pembayaran dapat ditransfer melalui: <strong>BCA Rek. 123456789 a.n CV. DAEDAN ENTERPRISE</strong>.</li>
                </ul>
            </section>
            @else
            <section class="mt-12 text-xs text-slate-600 leading-relaxed bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
                    👷 Ketentuan Teknis Pengerjaan
                </h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Semua peralatan kerja (kuas, scaffolding, pembersih, plamir) sepenuhnya disediakan oleh CV. DAEDAN ENTERPRISE.</li>
                    <li>Fasilitas air bersih, kelistrikan kerja, serta area penyimpanan bahan disediakan gratis oleh Pemberi Kerja.</li>
                    <li>Volumetri pengerjaan final akan dihitung bersama setelah pekerjaan selesai untuk penyesuaian akhir.</li>
                    <li>Pengujian dan serah terima pekerjaan (BAST) dilakukan bersama perwakilan resmi kedua belah pihak.</li>
                </ul>
            </section>
            @endif

            <section class="mt-12 flex justify-end">
                <div class="text-center w-48">
                    <p class="text-slate-400 text-xs mb-2">Hormat kami,</p>
                    <div class="h-24 w-40 relative mx-auto mb-2">
                        <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-full object-contain mx-auto">
                    </div>
                    <p class="font-bold text-slate-800 border-b border-slate-200 inline-block px-4 pb-1 text-sm">Didan Sirodjuddin</p>
                    <p class="text-slate-400 text-[10px] mt-1">President Director</p>
                </div>
            </section>

        </div>

        {{-- BUTTON ACTIONS --}}
        <div class="max-w-4xl mx-auto mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
            <button onclick="openNegotiationModal()" class="w-full sm:w-auto bg-white hover:bg-slate-50 text-slate-700 font-bold py-3.5 px-8 rounded-full border border-slate-250 transition duration-200 shadow-sm flex items-center justify-center gap-2 text-sm">
                💬 Ajukan Negosiasi Harga
            </button>
            <a href="https://wa.me/6281393044942?text=Halo%20Admin%20Quick.Deal,%20saya%20ingin%20bertanya%20mengenai%20surat%20penawaran%20nomor%2000{{ $offer->id }}..." 
               target="_blank"
               class="w-full sm:w-auto bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold py-3.5 px-8 rounded-full border border-emerald-200 transition duration-200 shadow-sm flex items-center justify-center gap-2 text-sm">
                🟢 Chat Admin WA
            </a>
            <a href="{{ route('po.create', $offer->id) }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg transform transition duration-200 hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                📄 Buat Purchase Order (PO)
            </a>
        </div>
    </div>

    {{-- MODAL AJUKAN NEGOSIASI --}}
    <div id="negotiationModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeNegotiationModal()"></div>

        <!-- Modal Content Container -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md p-6 border border-slate-100">
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-950" id="modal-title">💬 Ajukan Negosiasi</h3>
                        <p class="text-xs text-slate-400 mt-1">Isi formulir untuk mengajukan penawaran harga Anda.</p>
                    </div>
                    <button onclick="closeNegotiationModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-semibold leading-none">&times;</button>
                </div>

                <form action="{{ route('front.penawaran.negotiate', $offer->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Nama Klien / Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_klien" required placeholder="Contoh: PT. Sumber Makmur / Ibu Rina" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-3">
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">No. HP / WhatsApp (WA) <span class="text-red-500">*</span></label>
                        <input type="text" name="kontak" required placeholder="Contoh: 0899-8877-6655" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-3">
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Email Kontak <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="Contoh: rina@gmail.com" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-3">
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Harga yang Diajukan (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative mt-1 rounded-xl shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-slate-400 text-sm font-bold">Rp</span>
                            </div>
                            <input type="number" name="harga_pengajuan" required min="0" placeholder="Masukkan nominal harga..." class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5 pl-10 pr-3 font-bold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Contoh: Mohon diskon harga borongan pengecatan atau suplai produk..." class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2 px-3"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-150 flex justify-end gap-3">
                        <button type="button" onclick="closeNegotiationModal()" class="px-4 py-2 rounded-xl border border-slate-250 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm hover:shadow-md">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openNegotiationModal() {
            document.getElementById('negotiationModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeNegotiationModal() {
            document.getElementById('negotiationModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    {{-- Include Floating WhatsApp Widget --}}
    @include('partials.whatsapp-btn')

</body>
</html>
