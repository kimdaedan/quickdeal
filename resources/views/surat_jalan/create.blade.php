@extends('layouts.app')

@section('content')
{{-- LOGIKA TANGGAL INDONESIA OTOMATIS --}}
@php
    $days = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $now = \Carbon\Carbon::now();
    $hariIni = $days[$now->format('l')];
    $tanggalIni = $now->format('d');
    $bulanIni = $months[(int)$now->format('m')];
    $tahunIni = $now->format('Y');
@endphp

<div class="container mx-auto my-12 px-4">
    <div class="max-w-5xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg">

        <div class="flex justify-between items-center mb-8 print:hidden">
            <h1 class="text-2xl font-bold text-gray-800">Buat Surat Jalan</h1>
            <a href="{{ route('histori.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                &larr; Kembali ke Histori
            </a>
        </div>

        {{-- Menampilkan Error Validasi jika ada --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                <p class="font-bold">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('surat_jalan.store', $offer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- KOP SURAT -->
            <header class="w-full mb-6">
                <div class="w-full">
                    <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat" class="w-full h-auto">
                </div>
            </header>

            <!-- JUDUL -->
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold underline uppercase">SURAT JALAN / PENGIRIMAN BARANG</h2>
                <div class="flex justify-center items-center mt-2 gap-2">
                    <span class="text-gray-700 font-medium">No.</span>
                    <input type="text" name="no_surat" value="{{ old('no_surat', $noSurat) }}" class="border-b border-gray-400 focus:outline-none focus:border-blue-500 text-center w-64" required>
                </div>
            </div>

            <!-- ISI DOKUMEN -->
            <div class="text-justify leading-relaxed text-gray-800 space-y-4">
                
                <div class="grid grid-cols-2 gap-8">
                    <!-- KOLOM KIRI: INFO PENGIRIM -->
                    <div>
                        <p class="mb-2 font-bold uppercase text-sm text-gray-500">PENGIRIM</p>
                        <div class="grid grid-cols-[100px_10px_1fr] gap-y-2 text-sm">
                            <span>Nama Petugas</span><span>:</span>
                            <input type="text" name="sumber_pengirim" value="{{ old('sumber_pengirim', $sumberPengirim) }}" class="w-full border-b px-1 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <!-- KOLOM KANAN: INFO PENERIMA -->
                    <div>
                        <p class="mb-2 font-bold uppercase text-sm text-gray-500">PENERIMA / TUJUAN</p>
                        <div class="grid grid-cols-[100px_10px_1fr] gap-y-2 text-sm">
                            <span>Nama</span><span>:</span>
                            <input type="text" name="penerima_nama" value="{{ old('penerima_nama', $offer->nama_klien) }}" class="w-full border-b px-1 focus:outline-none focus:border-blue-500" required>
                            
                            <span>Instansi/PT</span><span>:</span>
                            <input type="text" name="penerima_instansi" value="{{ old('penerima_instansi') }}" class="w-full border-b px-1 focus:outline-none focus:border-blue-500" placeholder="(Opsional)">
                            
                            <span>Alamat</span><span>:</span>
                            <textarea name="penerima_alamat" rows="2" class="w-full border px-2 py-1 rounded focus:outline-none focus:border-blue-500" required>{{ old('penerima_alamat', $offer->client_details) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="mb-2 font-bold text-gray-700">Daftar Produk yang Dikirim:</p>
                    <table class="w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-center w-12">No</th>
                                <th class="border px-4 py-2">Nama Produk / Barang</th>
                                <th class="border px-4 py-2 text-center w-24">QTY</th>
                                <th class="border px-4 py-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offer->items as $index => $item)
                            <tr>
                                <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="border px-4 py-2 font-medium">{{ $item->nama_produk }}</td>
                                <td class="border px-4 py-2 text-center">{{ $item->volume }} {{ $item->satuan }}</td>
                                <td class="border px-4 py-2 text-gray-600">{{ $item->deskripsi_tambahan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <label class="font-bold text-sm text-gray-700 block mb-1">Catatan Pengiriman (Opsional):</label>
                    <textarea name="catatan_pengiriman" rows="3" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">{{ old('catatan_pengiriman') }}</textarea>
                </div>
            </div>

            <!-- TANDA TANGAN -->
            <div class="mt-12 flex justify-between text-center px-8">
                <div>
                    <p class="mb-20 font-bold">Penerima Barang</p>
                    <p class="font-bold underline uppercase" id="preview-penerima">{{ old('penerima_nama', $offer->nama_klien) }}</p>
                    <p class="text-sm" id="preview-instansi">...</p>
                </div>
                <div>
                    <p class="mb-20 font-bold">Pengirim Barang</p>
                    <p class="font-bold underline uppercase" id="preview-pengirim">{{ old('sumber_pengirim', $sumberPengirim) }}</p>
                </div>
            </div>

            <!-- DOKUMENTASI -->
            <fieldset class="border-t-2 border-gray-300 pt-8 mt-12 print:hidden">
                <legend class="text-xl font-bold text-gray-800 px-4 uppercase bg-gray-100 rounded">Dokumentasi Produk / Barang (Opsional)</legend>
                <div id="documentation-rows" class="space-y-6 mt-4">
                    <div class="doc-row grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded border relative">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">FOTO BARANG (SAAT DIKIRIM)</label>
                            <input type="file" name="before_images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewImageDynamic(this)">
                            <img class="mt-2 h-32 object-cover rounded hidden preview-img">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">FOTO BARANG DITERIMA / TANDA TANGAN</label>
                            <input type="file" name="after_images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100" onchange="previewImageDynamic(this)">
                            <img class="mt-2 h-32 object-cover rounded hidden preview-img">
                        </div>
                        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-row-btn" title="Hapus Baris">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" id="add-row-btn" class="bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded hover:bg-gray-300 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        Tambah Baris Foto
                    </button>
                </div>
            </fieldset>

            <div class="mt-12 text-center print:hidden">
                <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-blue-700 hover:shadow-xl transition transform hover:-translate-y-1">
                    Simpan & Buat Surat Jalan
                </button>
            </div>

        </form>
    </div>
</div>

<template id="doc-row-template">
    <div class="doc-row grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded border relative mt-4">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">FOTO BARANG (SAAT DIKIRIM)</label>
            <input type="file" name="before_images[]" accept="image/*" class="block w-full text-sm" onchange="previewImageDynamic(this)">
            <img class="mt-2 h-32 object-cover rounded hidden preview-img">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">FOTO BARANG DITERIMA / TANDA TANGAN</label>
            <input type="file" name="after_images[]" accept="image/*" class="block w-full text-sm" onchange="previewImageDynamic(this)">
            <img class="mt-2 h-32 object-cover rounded hidden preview-img">
        </div>
        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-row-btn" title="Hapus Baris">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
</template>

<script>
    function previewImageDynamic(input) {
        const preview = input.nextElementSibling;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('documentation-rows');
        const addBtn = document.getElementById('add-row-btn');
        const template = document.getElementById('doc-row-template');

        addBtn.addEventListener('click', function() {
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row-btn')) {
                if (container.querySelectorAll('.doc-row').length > 1) {
                    e.target.closest('.doc-row').remove();
                } else {
                    alert("Minimal harus ada satu baris dokumentasi.");
                }
            }
        });

        // Live Preview form to Tanda Tangan
        document.querySelector('input[name="penerima_nama"]').addEventListener('input', function(e) {
            document.getElementById('preview-penerima').innerText = e.target.value.trim() || '__________';
        });
        document.querySelector('input[name="penerima_instansi"]').addEventListener('input', function(e) {
            document.getElementById('preview-instansi').innerText = e.target.value.trim();
        });
        document.querySelector('input[name="sumber_pengirim"]').addEventListener('input', function(e) {
            document.getElementById('preview-pengirim').innerText = e.target.value.trim() || '__________';
        });
    });
</script>
@endsection
