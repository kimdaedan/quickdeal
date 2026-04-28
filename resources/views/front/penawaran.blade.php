<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penawaran Publik - Quick.Deal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .hover-card { transition: all 0.3s ease; }
        .hover-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="text-slate-900 flex flex-col min-h-screen">

    {{-- Navbar --}}
    <nav class="fixed w-full z-50 glass-effect border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
           <a href="{{ route('front.landing') }}" class="flex items-center gap-3">
                <span class="text-xl font-extrabold tracking-tight">Quick<span class="text-blue-600">.Deal</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('front.landing') }}" class="text-slate-600 hover:text-blue-600 font-medium transition">Beranda</a>
                <a href="{{ route('dashboard') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-800 transition">Login</a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="pt-32 pb-12 px-6 bg-gradient-to-b from-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                Daftar Penawaran <span class="text-blue-600">Publik</span>
            </h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                Eksplorasi kumpulan data penawaran harga produk dan proyek layanan kami.
            </p>
        </div>
    </section>

    {{-- Filter & Content --}}
    <section class="py-10 px-6 flex-grow">
        <div class="max-w-7xl mx-auto">
            
            {{-- Filter Tabs --}}
            <div class="flex justify-center mb-10">
                <div class="inline-flex bg-slate-100 p-1.5 rounded-2xl">
                    <a href="{{ route('front.penawaran.index') }}" 
                       class="px-6 py-2.5 rounded-xl font-semibold text-sm transition {{ empty($type) ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('front.penawaran.index', ['type' => 'produk']) }}" 
                       class="px-6 py-2.5 rounded-xl font-semibold text-sm transition {{ $type == 'produk' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Produk
                    </a>
                    <a href="{{ route('front.penawaran.index', ['type' => 'jasa']) }}" 
                       class="px-6 py-2.5 rounded-xl font-semibold text-sm transition {{ $type == 'jasa' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        Proyek / Jasa
                    </a>
                </div>
            </div>

            {{-- Grid Penawaran --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($offers as $offer)
                    <a href="{{ route('front.penawaran.show', $offer->id) }}" class="bg-white rounded-3xl p-6 border border-slate-100 hover-card block relative overflow-hidden group">
                        
                        <div class="absolute top-0 right-0 p-4">
                           @if($offer->jenis_penawaran == 'produk')
                                <span class="bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">Produk</span>
                            @else
                                <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100">Proyek</span>
                            @endif
                        </div>

                        <div class="text-sm font-medium text-slate-400 mb-2 mt-2">
                            {{ $offer->created_at->format('d M Y') }}
                        </div>

                        <h3 class="text-xl font-bold text-slate-800 mb-2">
                            {{ $offer->judul_publik ?: 'SP-' . $offer->created_at->format('Y') . '/' . str_pad($offer->id, 4, '0', STR_PAD_LEFT) }}
                        </h3>

                        {{-- Nama Klien dirahasiakan sebagian --}}
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                🏢
                            </div>
                            <span class="text-slate-600 font-medium text-sm">
                                Anonim / Publik
                            </span>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center group-hover:border-blue-100 transition">
                            <span class="text-slate-500 text-sm font-medium">Total Nilai</span>
                            <span class="font-bold text-slate-900 text-lg">
                                @php
                                    $totalProduk = $offer->items->sum(function($item) { return $item->volume * $item->harga_per_m2; });
                                    $totalJasa = $offer->jasaItems->sum('harga_jasa');
                                    $gTotal = $totalProduk + $totalJasa;
                                @endphp
                                @if($offer->hilangkan_grand_total)
                                    Rp -
                                @else
                                    Rp {{ number_format($gTotal, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <object><a href="{{ route('po.create', $offer->id) }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-xl transition duration-200">
                                Buat PO
                            </a></object>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                            <span class="text-2xl">📭</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-700">Belum ada Penawaran Publik</h3>
                        <p class="text-slate-500 mt-2">Tidak ada data penawaran yang dipublish saat ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $offers->appends(['type' => $type])->links() }}
            </div>

        </div>
    </section>

    {{-- Footer --}}
    <footer class="mt-auto py-8 text-center text-slate-500 text-sm border-t border-slate-200 bg-white">
        &copy; 2026 Quick.Deal. All rights reserved.
    </footer>

</body>
</html>
