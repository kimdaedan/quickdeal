@extends('layouts.app')

@section('content')

@php
    // Setup Variabel
    $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
    $bulanAngka = $offer->created_at->format('n');
    $tahun      = $offer->created_at->format('Y');
    $noUrut     = str_pad($offer->id, 3, '0', STR_PAD_LEFT);
    $nomorSurat = sprintf('%s/SP-PRODUK/TGI/%s/%s', $noUrut, $bulanRomawi[$bulanAngka], $tahun);

    // Cek Opsi
    $hideGrandTotal = $offer->hilangkan_grand_total;
@endphp

<div class="container mx-auto my-12 px-4">

    <div class="max-w-4xl mx-auto mb-4 flex justify-between gap-2 print:hidden">
        <a href="{{ route('histori.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300 transition-colors flex items-center">&larr; Kembali</a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors flex items-center gap-2">🖨️ Print</button>
            <a href="{{ route('invoice.create_from_offer', $offer->id) }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition-colors flex items-center">Buat Invoice &rarr;</a>
        </div>
    </div>

    {{-- DAFTAR NEGOSIASI --}}
    @if($offer->negotiations->isNotEmpty())
    <div class="max-w-4xl mx-auto mb-6 bg-white border border-indigo-100 rounded-xl shadow-md p-6 print:hidden">
        <h3 class="text-base font-bold text-gray-850 mb-4 flex items-center gap-2">
            <span>💬</span> Pengajuan Negosiasi Klien ({{ $offer->negotiations->count() }})
        </h3>
        <div class="space-y-4 divide-y divide-gray-100">
            @foreach($offer->negotiations as $neg)
            <div class="pt-4 first:pt-0 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-800 text-sm">{{ $neg->nama_klien }}</span>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-indigo-150">WA: {{ $neg->kontak }}</span>
                        @if($neg->email)
                        <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-150">Email: {{ $neg->email }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">Diajukan pada: {{ $neg->created_at->format('d M Y H:i') }}</p>
                    @if($neg->catatan)
                    <p class="text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg border border-gray-100 italic mt-1.5">"{{ $neg->catatan }}"</p>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Harga Pengajuan</p>
                        <p class="text-base font-extrabold text-indigo-600">Rp {{ number_format($neg->harga_pengajuan, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($neg->status !== 'approved')
                        <form action="{{ route('negotiation.approve', $neg->id) }}" method="POST" onsubmit="return confirm('Setujui negosiasi ini? Penawaran akan diupdate ke harga pengajuan dan Invoice otomatis akan dibuat.');">
                            @csrf
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold py-1.5 px-3 rounded-lg shadow-sm transition flex items-center gap-1">
                                <span>✓</span> Setujui
                            </button>
                        </form>
                        @else
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-emerald-200">
                            Disetujui
                        </span>
                        @endif
                        <form action="{{ route('negotiation.destroy', $neg->id) }}" method="POST" onsubmit="return confirm('Hapus negosiasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 hover:bg-red-50 rounded-lg transition" title="Hapus Pengajuan">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg print:shadow-none print:p-0" id="surat-penawaran">

        <style>
    .font-times {
        font-family: 'Times New Roman', Times, serif;
    }
</style>

{{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6">
            <div class="w-full">
                {{-- Menggunakan class w-full agar gambar memenuhi lebar kontainer --}}
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="w-full h-auto">
            </div>
            {{-- Garis merah di bawah tetap dipertahankan atau dihapus sesuai keinginan --}}
            <div class="w-full border-b-[4px] border-[#d32f2f] mt-1"></div>
        </header>

        <section class="text-sm">

            <div class="space-y-1 mb-6">
                <p class="text-gray-700">Batam, {{ $offer->created_at->format('d F Y') }}</p>
                <p class="text-gray-700 font-bold">Nomor : {{ $nomorSurat }}</p>
                <p class="text-gray-700">Perihal : Penawaran Harga Produk</p>
            </div>

            <div class="mb-6">
                <p class="text-gray-600">Kepada Yth,</p>
                <h3 class="text-base font-bold text-gray-800 uppercase mt-1">{{ $offer->nama_klien }}</h3>
                @if($offer->client_details)
                    <p class="text-gray-700 max-w-lg mt-0.5">{{ $offer->client_details }}</p>
                @endif
            </div>

        </section>

        <section class="mb-4 text-sm text-gray-700">
            <p>Dengan Hormat,</p>
            <p class="mt-2 text-justify">Bersama surat ini, kami <strong>CV. DAEDAN ENTERPRISE</strong> mengajukan penawaran harga untuk produk supply kami dengan rincian sebagai berikut:</p>
        </section>

        <section class="mt-4">
            <table class="w-full text-left border-collapse border border-gray-800 text-xs">
                <thead class="bg-gray-800 text-white print-bg-black">
                    <tr>
                        <th class="py-1 px-1 border border-gray-600 text-center w-[5%]">No</th>
                        <th class="py-1 px-1 border border-gray-600 w-[25%]">Nama Produk</th>
                        <th class="py-1 px-1 border border-gray-600 w-[20%]">Keterangan</th>
                        <th class="py-1 px-1 border border-gray-600 w-[15%] text-right">Harga Satuan</th>
                        <th class="py-1 px-1 border border-gray-600 w-[5%] text-center">Qty</th>
                        <th class="py-1 px-1 border border-gray-600 w-[12%] text-right">Diskon</th>
                        <th class="py-1 px-1 border border-gray-600 w-[18%] text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
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
                        <tr class="border-b border-gray-300">
                            <td class="py-0.5 px-1 border-x border-gray-300 text-center align-middle">{{ $index + 1 }}</td>
                            <td class="py-0.5 px-1 border-x border-gray-300 font-bold text-gray-700 align-middle leading-tight">
                                {{ $item->nama_produk }}
                            </td>
                            <td class="py-0.5 px-1 border-x border-gray-300 text-[10px] align-middle leading-tight">
                                {!! nl2br(e($keterangan ?: '-')) !!}
                            </td>
                            <td class="py-0.5 px-1 border-x border-gray-300 text-right whitespace-nowrap align-middle">
                                Rp {{ number_format($harga, 0, ',', '.') }}
                            </td>
                            <td class="py-0.5 px-1 border-x border-gray-300 text-center align-middle">
                                {{ $qty }}
                            </td>
                            <td class="py-0.5 px-1 border-x border-gray-300 text-right text-red-600 text-[10px] whitespace-nowrap align-middle">
                                {{ $diskonNominal > 0 ? '- Rp ' . number_format($diskonNominal, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-0.5 px-1 border-x border-gray-300 text-right font-bold whitespace-nowrap align-middle">
                                Rp {{ number_format($totalBaris, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                @if(!$hideGrandTotal)
                    <tfoot>
                        @if($offer->diskon_global > 0)
                            <tr class="bg-gray-50 print:bg-white">
                                <td colspan="6" class="py-1 px-1 border border-gray-300 text-right font-semibold text-gray-600 text-xs">Subtotal</td>
                                <td class="py-1 px-1 border border-gray-300 text-right font-semibold whitespace-nowrap text-xs">
                                    Rp {{ number_format($subtotalSemua, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50 print:bg-white text-red-600">
                                <td colspan="6" class="py-1 px-1 border border-gray-300 text-right font-semibold text-xs">Diskon Tambahan (Global)</td>
                                <td class="py-1 px-1 border border-gray-300 text-right font-semibold whitespace-nowrap text-xs">
                                    - Rp {{ number_format($offer->diskon_global, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        <tr class="bg-gray-100 print:bg-gray-200">
                            <td colspan="6" class="py-2 px-1 border border-gray-300 text-right font-bold uppercase text-gray-900 text-sm">Grand Total</td>
                            <td class="py-2 px-1 border border-gray-300 text-right font-bold text-sm text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </section>

        <section class="mt-6 text-xs text-gray-700 break-inside-avoid">
            <h4 class="font-bold underline mb-1">Keterangan:</h4>
            <ul class="list-disc ml-4 space-y-0">
                <li>Harga sudah termasuk PPN.</li>
                <li>Barang yang sudah dibeli tidak dapat dikembalikan.</li>
                <li>Rekening: <strong>BCA a.n CV. DAEDAN ENTERPRISE</strong>.</li>

            </ul>
        </section>

        <section class="mt-8 flex justify-end break-inside-avoid">
            <div class="text-center w-64">
                <p class="mb-4 text-sm">Batam, {{ $offer->created_at->format('d F Y') }}</p>
                <p class="mb-1 text-sm font-semibold">Hormat Kami,</p>
                <div class="h-20 flex justify-center items-center my-1">
                    @if(file_exists(public_path('images/ttd.png')))
                        <img src="{{ asset('images/ttd.png') }}" class="h-20 object-contain">
                    @else <br><br> @endif
                </div>
                <p class="font-bold text-gray-800 border-b border-gray-800 pb-1 text-sm">Didan Sirodjuddin</p>
                <p class="text-gray-600 text-xs mt-1">President Director</p>
            </div>
        </section>

    </div>
</div>

<style>
    @media print {
        @page { margin: 0; size: A4; }
        body * { visibility: hidden; }
        #surat-penawaran, #surat-penawaran * { visibility: visible; }
        #surat-penawaran {
            position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 1cm;
            box-shadow: none !important; border: none !important; background-color: white !important;
        }
        .print-bg-black { background-color: #1f2937 !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .print\:hidden { display: none !important; }
        tr, td, th { page-break-inside: avoid; }
        .break-inside-avoid { page-break-inside: avoid; }
    }
</style>
@endsection