@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 flex-grow">
    <div class="max-w-7xl mx-auto">

        {{-- Header & Tombol --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Histori Penawaran</h1>
            <div class="flex gap-2">
                <a href="{{ route('penawaran.create_product') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition shadow-sm text-sm">
                    + Penawaran Produk
                </a>
                <a href="{{ route('penawaran.create_combined') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition shadow-sm text-sm">
                    + Penawaran Proyek
                </a>
            </div>
        </div>

        {{-- Form Filter & Pencarian --}}
        <form action="{{ route('histori.index') }}" method="GET" class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search Input --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari Kata Kunci</label>
                    <input type="text"
                           name="search"
                           placeholder="Cari Nama Klien / No. Surat..."
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                           value="{{ $search ?? '' }}">
                </div>
                
                {{-- Jenis Penawaran Filter --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jenis Penawaran</label>
                    <select name="jenis" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Semua Jenis</option>
                        <option value="produk" {{ ($jenisFilter ?? '') === 'produk' ? 'selected' : '' }}>Produk</option>
                        <option value="proyek" {{ ($jenisFilter ?? '') === 'proyek' ? 'selected' : '' }}>Proyek</option>
                        <option value="publik" {{ ($jenisFilter ?? '') === 'publik' ? 'selected' : '' }}>Publik</option>
                    </select>
                </div>
                
                {{-- Submit Button --}}
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition text-sm">
                        Terapkan Filter
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 pt-4 border-t border-gray-150">
                {{-- Start Date --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
                    <input type="date"
                           name="start_date"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                           value="{{ $startDate ?? '' }}">
                </div>
                
                {{-- End Date --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Selesai</label>
                    <input type="date"
                           name="end_date"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                           value="{{ $endDate ?? '' }}">
                </div>
                
                {{-- Reset Button --}}
                <div class="md:col-span-2 flex items-end justify-end">
                    <a href="{{ route('histori.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">
                        Reset Filter
                    </a>
                </div>
            </div>
        </form>

        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        {{-- TABEL HISTORI --}}
        <div class="bg-white shadow-md rounded-lg overflow-x-auto min-h-[400px]">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 rounded-tl-lg text-center w-24">Action</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">No. Surat</th>
                        <th scope="col" class="px-6 py-3 text-center">Jenis</th>
                        <th scope="col" class="px-6 py-3">Nama Klien</th>
                        <th scope="col" class="px-6 py-3">Detail</th>
                        <th scope="col" class="px-6 py-3 text-right rounded-tr-lg">Total Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($offers as $offer)
                    <tr class="bg-white hover:bg-gray-50 transition-colors align-top">

                        {{-- 1. ACTION --}}
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1.5 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Options
                                    <svg class="-mr-1 ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                                    <div class="py-1" role="menu">
                                        <a href="{{ route('histori.show', ['offer' => $offer->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">👁️ Lihat / Print</a>
                                        <div class="border-t border-gray-100 my-1"></div>
                                        
                                        <a href="{{ route('invoice.create_from_offer', ['offer' => $offer->id]) }}" class="text-green-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">💰 Buat Invoice</a>
                                        
                                        @if($offer->jenis_penawaran == 'produk')
                                            <a href="{{ route('surat_jalan.create', ['offer' => $offer->id]) }}" class="text-teal-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">📦 Buat Surat Jalan</a>
                                        @else
                                            <a href="{{ route('bast.create', ['offer' => $offer->id]) }}" class="text-teal-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">🤝 Buat BAST</a>
                                        @endif
                                        
                                        <a href="{{ route('histori.recap', ['offer' => $offer->id]) }}" class="text-blue-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">📋 Buat Rekapan</a>
                                        <div class="border-t border-gray-100 my-1"></div>
                                        @if($offer->jenis_penawaran == 'produk')
                                            <a href="{{ route('penawaran.edit_product', ['offer' => $offer->id]) }}" class="text-yellow-600 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">✏️ Edit Produk</a>
                                        @else
                                            <a href="{{ route('histori.edit', ['offer' => $offer->id]) }}" class="text-yellow-600 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">✏️ Edit Proyek</a>
                                        @endif
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <form action="{{ route('histori.toggle_publish', ['offer' => $offer->id]) }}" method="POST"
                                            @if(!$offer->is_public)
                                                onsubmit="let judul = prompt('Masukkan judul Penawaran:', '{{ $offer->judul_publik ?: 'Penawaran ' . ucfirst($offer->jenis_penawaran) }}'); if(judul === null) { return false; } document.getElementById('judul_publik_{{ $offer->id }}').value = judul;"
                                            @endif
                                        >
                                            @csrf
                                            <input type="hidden" name="judul_publik" id="judul_publik_{{ $offer->id }}">
                                            <button type="submit" class="w-full text-left text-fuchsia-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">
                                                {{ $offer->is_public ? '🚫 Batal Publish' : '🌐 Publish ke Web' }}
                                            </button>
                                        </form>
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <form action="{{ route('histori.destroy', ['offer' => $offer->id]) }}" method="POST" onsubmit="return confirm('Hapus penawaran ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left text-red-700 block px-4 py-2 text-sm hover:bg-gray-100">🗑️ Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $offer->created_at->format('d M Y') }}</td>

                        {{-- NO SURAT --}}
                        <td class="px-6 py-4 font-medium whitespace-nowrap">
                            SP-{{ $offer->created_at->format('Y') }}/{{ str_pad($offer->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- JENIS --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-1.5 justify-center">
                                @if($offer->jenis_penawaran == 'produk')
                                    <span class="bg-blue-50 text-blue-700 dark:bg-blue-900/35 dark:text-blue-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-blue-200 dark:border-blue-800/40 inline-block whitespace-nowrap">Produk</span>
                                @else
                                    <span class="bg-slate-100 text-slate-700 dark:bg-slate-850 dark:text-slate-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700/50 inline-block whitespace-nowrap">Proyek</span>
                                @endif
                                
                                @if($offer->is_public)
                                    <span class="bg-fuchsia-50 text-fuchsia-700 dark:bg-fuchsia-900/35 dark:text-fuchsia-300 text-xs font-bold px-2 py-1 rounded-lg border border-fuchsia-200 dark:border-fuchsia-800/40 inline-flex items-center gap-1 whitespace-nowrap">
                                        <span>🌐</span> <span>Publik</span>
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- NAMA KLIEN --}}
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-normal min-w-[200px] max-w-[300px] leading-snug">
                            <div class="flex flex-col gap-1 items-start">
                                <span>{{ $offer->nama_klien }}</span>
                                @if($offer->negotiations_count > 0)
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-850 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-red-300 animate-pulse">
                                        💬 {{ $offer->negotiations_count }} Negosiasi Baru
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- DETAIL --}}
                        <td class="px-6 py-4 text-sm text-gray-600 min-w-[200px] whitespace-normal">
                            {{ Str::limit($offer->client_details, 50) }}
                        </td>

                        {{-- TOTAL HARGA (DIPERBAIKI: HITUNG ULANG MANUAL) --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap font-bold text-gray-800">
                            @php
                                // Hitung Total Produk (Volume * Harga)
                                $totalProduk = $offer->items->sum(function($item) {
                                    return $item->volume * $item->harga_per_m2;
                                });

                                // Hitung Total Jasa (Harga Jasa di DB sudah Total)
                                $totalJasa = $offer->jasaItems->sum('harga_jasa');

                                // Grand Total
                                $grandTotal = $totalProduk + $totalJasa;
                            @endphp
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <span class="text-lg font-medium">Belum ada histori penawaran.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 pb-12">
            {{ $offers->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection