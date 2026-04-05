@extends('layouts.app')

@section('content')
{{-- LOGIKA MEMFORMAT TANGGAL DARI DATABASE KE TEKS INDONESIA --}}
@php
use Carbon\Carbon;
$date = Carbon::parse($suratJalan->tanggal);

$days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
$months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

$hari = $days[$date->format('l')];
$tanggal = $date->format('d');
$bulan = $months[(int)$date->format('m')];
$tahun = $date->format('Y');
@endphp

<div class="container mx-auto my-12 px-4">

    <!-- Tombol Aksi (Akan hilang saat print) -->
    <div class="max-w-5xl mx-auto mb-4 flex justify-between gap-2 print:hidden">
        <a href="{{ route('surat_jalan.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300 transition-colors">
            &larr; Kembali ke Histori
        </a>
        <a href="{{ route('surat_jalan.print', $suratJalan->id) }}" target="_blank" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors">
            🖨️ Print Dokumen (PDF)
        </a>
    </div>

    <!-- Area Dokumen Cetak -->
    <!-- ID 'dokumen-sj' ini yang akan kita isolasi saat print -->
    <div class="max-w-5xl mx-auto bg-white p-8 md:p-16 shadow-lg rounded-lg" id="dokumen-sj">

        {{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6">
            <div class="w-full">
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat" class="w-full h-auto">
            </div>
            <div class="w-full border-b-[4px] border-[#d32f2f] mt-1"></div>
        </header>


        <!-- JUDUL -->
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold underline uppercase">SURAT JALAN / PENGIRIMAN BARANG</h2>
            <p class="font-medium mt-1">No. {{ $suratJalan->no_surat }}</p>
        </div>

        <!-- ISI -->
        <div class="text-justify leading-relaxed text-gray-900 space-y-4 text-base font-serif">
            <p class="mb-4">
                Telah dikirimkan barang dengan rincian sebagai berikut pada hari <strong>{{ $hari }}</strong>, <strong>{{ $tanggal }} {{ $bulan }} {{ $tahun }}</strong>:
            </p>

            <div class="grid grid-cols-2 gap-8 mb-6">
                <!-- PENGIRIM -->
                <div>
                    <h3 class="font-bold underline mb-2">PENGIRIM</h3>
                    <table class="w-full">
                        <tr>
                            <td class="align-top w-24">Nama</td>
                            <td class="align-top w-4">:</td>
                            <td class="align-top font-bold">{{ $suratJalan->sumber_pengirim }}</td>
                        </tr>
                    </table>
                </div>

                <!-- PENERIMA -->
                <div>
                    <h3 class="font-bold underline mb-2">PENERIMA / TUJUAN</h3>
                    <table class="w-full">
                        <tr>
                            <td class="align-top w-24">Nama</td>
                            <td class="align-top w-4">:</td>
                            <td class="align-top font-bold">{{ $suratJalan->penerima_nama }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Instansi/PT</td>
                            <td class="align-top">:</td>
                            <td class="align-top">{{ $suratJalan->penerima_instansi }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Alamat</td>
                            <td class="align-top">:</td>
                            <td class="align-top">{{ $suratJalan->penerima_alamat }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- RINCIAN PRODUK -->
            <div class="mt-8">
                <p class="font-bold mb-2">RINCIAN BARANG PENGIRIMAN:</p>
                <table class="w-full border-collapse border border-gray-800 text-sm font-sans mb-4">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-800 px-3 py-2 text-center w-12">No</th>
                            <th class="border border-gray-800 px-3 py-2">Nama Barang / Deskripsi</th>
                            <th class="border border-gray-800 px-3 py-2 text-center w-24">QTY</th>
                            <th class="border border-gray-800 px-3 py-2">Catatan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suratJalan->offer->items as $index => $item)
                        <tr>
                            <td class="border border-gray-800 px-3 py-1 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-800 px-3 py-1">{{ $item->nama_produk }}</td>
                            <td class="border border-gray-800 px-3 py-1 text-center">{{ $item->volume }} {{ $item->satuan }}</td>
                            <td class="border border-gray-800 px-3 py-1">{{ $item->deskripsi_tambahan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($suratJalan->catatan_pengiriman)
                <p class="mt-4"><strong>Catatan Pengiriman:</strong><br/>
                {{ $suratJalan->catatan_pengiriman }}</p>
                @endif
            </div>

            <p class="mt-6">Demikian Surat Jalan ini dibuat untuk dipergunakan sebagaimana mestinya. Harap barang dicek kondisinya dengan baik saat diterima.</p>
        </div>

        <!-- TANDA TANGAN -->
        <div class="mt-16 flex justify-between text-center px-4 font-serif">
            <div class="w-1/2">
                <p class="mb-24">Penerima Barang</p>
                <p class="font-bold underline uppercase">{{ $suratJalan->penerima_nama }}</p>
                <p>{{ $suratJalan->penerima_instansi }}</p>
            </div>
            <div class="w-1/2">
                <p class="mb-24">Pengirim Barang</p>
                <p class="font-bold underline uppercase">{{ $suratJalan->sumber_pengirim }}</p>
            </div>
        </div>

        <!-- HALAMAN BARU: LAMPIRAN FOTO -->
        <div class="page-break"></div>

        <div class="mt-8 font-sans">
            <h2 class="text-xl font-bold text-center underline uppercase mb-8">LAMPIRAN DOKUMENTASI BARANG</h2>

            <div class="grid grid-cols-2 gap-8">
                <!-- Kolom Before -->
                <div class="space-y-8">
                    <h3 class="text-md font-bold text-center bg-gray-200 py-2 border border-gray-400">SAAT DIKIRIM (SEBELUM)</h3>
                    @if(count($beforeImages) > 0)
                    @foreach($beforeImages as $img)
                    <div class="border border-gray-300 p-2 bg-gray-50 shadow-sm">
                        <div class="aspect-w-4 aspect-h-3">
                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-64 object-cover" alt="Foto Sebelum">
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-center text-gray-500 italic">Tidak ada foto dokumentasi sebelum.</p>
                    @endif
                </div>

                <!-- Kolom After -->
                <div class="space-y-8">
                    <h3 class="text-md font-bold text-center bg-gray-200 py-2 border border-gray-400">DITERIMA (SESUDAH)</h3>
                    @if(count($afterImages) > 0)
                    @foreach($afterImages as $img)
                    <div class="border border-gray-300 p-2 bg-gray-50 shadow-sm">
                        <div class="aspect-w-4 aspect-h-3">
                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-64 object-cover" alt="Foto Sesudah">
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-center text-gray-500 italic">Tidak ada foto dokumentasi sesudah.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #dokumen-sj, #dokumen-sj * {
            visibility: visible;
        }
        #dokumen-sj {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none !important;
            border: none !important;
        }
        body {
            background-color: white !important;
            font-family: 'Times New Roman', serif;
        }
        .print\:hidden {
            display: none !important;
        }
        .page-break {
            page-break-before: always;
        }
        .bg-gray-200 {
            background-color: #e5e7eb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @page {
            margin: 2cm;
            size: A4 portrait;
        }
    }
</style>
@endsection
