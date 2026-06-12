<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penawaran Publik - Quick.Deal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-effect { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); }
        .hover-card { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .hover-card:hover { transform: translateY(-6px); box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.08), 0 0 0 1px rgba(99, 102, 241, 0.05); }
    </style>
</head>
<body class="text-slate-900 flex flex-col min-h-screen">

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 glass-effect border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
           <a href="{{ route('front.landing') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-app.png') }}" alt="Logo" class="h-10 w-auto object-contain">
                <span class="text-xl font-extrabold tracking-tight">Quick<span class="text-indigo-600">.Deal</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('front.landing') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition text-sm">Beranda</a>
                <a href="{{ route('dashboard') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-800 transition shadow-sm">Dashboard</a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="pt-36 pb-16 px-6 bg-gradient-to-b from-indigo-50/50 via-slate-50 to-slate-50 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.05),transparent_45%)]"></div>
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-4 py-1.5 rounded-full border border-indigo-100 uppercase tracking-wider">Katalog Terbuka</span>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mt-4 mb-4 leading-tight">
                Daftar Penawaran <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Publik</span>
            </h1>
            <p class="text-base md:text-lg text-slate-500 max-w-xl mx-auto">
                Telusuri dan kelola katalog penawaran harga produk dan paket proyek layanan resmi kami secara transparan.
            </p>
        </div>
    </section>

    {{-- Filter & Content --}}
    <section class="py-6 px-6 flex-grow">
        <div class="max-w-7xl mx-auto">
            
            {{-- Filter Tabs --}}
            <div class="flex justify-center mb-10">
                <div class="inline-flex bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200/50">
                    <a href="{{ route('front.penawaran.index') }}" 
                       class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition {{ empty($type) ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('front.penawaran.index', ['type' => 'produk']) }}" 
                       class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition {{ $type == 'produk' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Produk
                    </a>
                    <a href="{{ route('front.penawaran.index', ['type' => 'jasa']) }}" 
                       class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition {{ $type == 'jasa' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Proyek / Jasa
                    </a>
                </div>
            </div>

            {{-- Grid Penawaran --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($offers as $offer)
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 hover-card relative overflow-hidden group flex flex-col justify-between h-full shadow-sm">
                        <div>
                            {{-- Badge --}}
                            <div class="flex justify-between items-center mb-4">
                               <span class="text-xs font-semibold text-slate-400">
                                   {{ $offer->created_at->format('d M Y') }}
                               </span>
                               @if($offer->jenis_penawaran == 'produk')
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-emerald-100">🛍️ Produk</span>
                                @else
                                    <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-indigo-100">🛠️ Proyek Jasa</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-slate-800 mb-3 group-hover:text-indigo-600 transition leading-snug">
                                {{ $offer->judul_publik ?: 'Surat Penawaran #' . str_pad($offer->id, 4, '0', STR_PAD_LEFT) }}
                            </h3>

                            <div class="flex items-center gap-2 mb-6">
                                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-sm border border-slate-100">
                                    🏢
                                </div>
                                <span class="text-slate-500 font-medium text-xs">
                                    Publik / Anonim
                                </span>
                            </div>
                        </div>

                        <div>
                            {{-- Total Section --}}
                            <div class="pt-4 border-t border-slate-100 flex justify-between items-center mb-4">
                                <span class="text-slate-400 text-xs font-medium uppercase tracking-wider">Estimasi Nilai</span>
                                <span class="font-extrabold text-slate-900 text-base">
                                    @php
                                        $totalProduk = $offer->items->sum(function($item) { return $item->volume * $item->harga_per_m2; });
                                        $totalJasa = $offer->jasaItems->sum('harga_jasa');
                                        $gTotal = $totalProduk + $totalJasa;
                                    @endphp
                                    @if($offer->hilangkan_grand_total)
                                        <span class="text-slate-400 font-medium text-sm">Tersembunyi</span>
                                    @else
                                        Rp {{ number_format($gTotal, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <a href="{{ route('front.penawaran.show', $offer->id) }}" class="block text-center bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs border border-slate-200/60 transition duration-200">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('po.create', $offer->id) }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-200 shadow-sm hover:shadow-md">
                                    Buat PO
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-150 p-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100">
                            <span class="text-2xl">📭</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum ada Penawaran Publik</h3>
                        <p class="text-slate-400 text-sm mt-1">Tidak ada data penawaran yang dipublikasikan saat ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $offers->appends(['type' => $type])->links() }}
            </div>

        </div>
    </section>

    {{-- Footer --}}
    <footer class="mt-auto py-8 text-center text-slate-400 text-xs border-t border-slate-100 bg-white">
        &copy; 2026 Quick.Deal. Dibuat untuk Efisiensi & Transparansi Kerja.
    </footer>

</body>
</html>
