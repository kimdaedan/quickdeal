@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Penawaran Jasa Baru</h1>
            <p class="text-sm text-gray-500">Isi formulir di bawah untuk membuat penawaran pengerjaan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-blue-600 transition">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <form id="offer-form" action="{{ route('penawaran.store_combined') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <!-- 1. Informasi Klien -->
            <div class="mb-8 border-b border-gray-100 pb-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-md text-xs">1</span> Informasi Klien
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Klien / Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_klien" value="{{ old('nama_klien') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: PT. Maju Jaya / Bpk. Andi">
                        @error('nama_klien') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Detail Proyek (Opsional)</label>
                        <input type="text" name="client_details" value="{{ old('client_details') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Renovasi Ruko Lantai 2">
                        @error('client_details') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Detail Jasa -->
            <div class="mb-8 border-b border-gray-100 pb-6">
                <div class="flex justify-between items-end mb-4">
                    <h2 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-md text-xs">2</span> Detail Jasa
                    </h2>
                </div>

                <div id="product-rows-container" class="space-y-3">
                    <!-- Baris produk akan ditambahkan di sini -->
                </div>

                <div class="mt-4">
                    <button type="button" id="add-product-row-btn" class="flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors border border-dashed border-blue-300 w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Baris Jasa
                    </button>
                </div>
            </div>

            <!-- 3. Pengaturan Dokumen & Total -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="w-full md:w-1/2 space-y-3 bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-gray-700 text-sm mb-2 uppercase tracking-wide">Pengaturan Dokumen</h3>
                    
                    <div class="flex items-center">
                        <input id="hilangkan_grand_total"
                            name="hilangkan_grand_total"
                            type="checkbox"
                            value="1"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                            {{ old('hilangkan_grand_total') ? 'checked' : '' }}>
                        <label for="hilangkan_grand_total" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                            Sembunyikan Grand Total di Cetakan
                        </label>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm text-gray-700 font-bold mb-1">Diskon Global / Tambahan (Rp)</label>
                        <input type="number" id="diskon_global_input" name="diskon_global" value="0" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="w-full md:w-1/2 text-right">
                    <div class="flex justify-between text-gray-600 text-sm mb-2">
                        <span class="font-bold">Total Harga Jasa:</span>
                        <span id="display-subtotal" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-red-600 text-sm mb-2">
                        <span class="font-bold">Diskon:</span>
                        <span id="display-diskon" class="font-semibold">- Rp 0</span>
                    </div>
                    <hr class="my-2 border-gray-300">
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Grand Total</p>
                    <div id="total_keseluruhan" class="text-4xl font-extrabold text-blue-800 mt-1">Rp 0</div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 text-lg flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan & Buat Penawaran
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template Jasa -->
<template id="product-row-template">
    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end p-4 border border-gray-200 rounded-lg bg-gray-50 relative hover:shadow-sm transition-shadow">
        
        <div class="md:col-span-3">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Jasa</label>
            <select class="product-select w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih --</option>
                @foreach ($products as $product)
                <option value="{{ $product->nama_jasa }}" 
                        data-harga="{{ $product->harga }}" 
                        data-kategori="{{ $product->kategori }}" 
                        data-satuan="{{ $product->satuan }}">
                    {{ $product->nama_jasa }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-3">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Kategori</label>
            <input type="text" class="area-input w-full rounded-md border-gray-300 bg-white text-sm" readonly placeholder="Otomatis">
        </div>

        <div class="md:col-span-1">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1 flex items-center justify-between">
                <span>VOL <span class="satuan-label text-[10px] text-gray-400"></span></span>
            </label>
            <input type="number" step="0.01" value="1" class="volume-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 text-center">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Harga Satuan</label>
            <input type="number" class="harga-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Subtotal</label>
            <input type="text" class="total-output w-full bg-gray-200 border-gray-300 rounded-md text-sm font-bold text-gray-700 cursor-not-allowed" readonly>
            
            <!-- Hidden inputs untuk form submit -->
            <input type="hidden" class="hidden-satuan">
        </div>

        <div class="absolute top-2 right-2 md:static md:col-span-1 md:flex md:justify-end md:pb-1">
            <button type="button" class="remove-row-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors" title="Hapus Baris">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const diskonGlobalInput = document.getElementById('diskon_global_input');
        const displaySubtotal = document.getElementById('display-subtotal');
        const displayDiskon = document.getElementById('display-diskon');
        const totalKeseluruhanDisplay = document.getElementById('total_keseluruhan');
        const hilangkanGrandTotal = document.getElementById('hilangkan_grand_total');
        
        diskonGlobalInput.addEventListener('input', calculateAllTotals);
        hilangkanGrandTotal.addEventListener('change', calculateAllTotals);
        
        let productRowIndex = 0;

        function formatRupiah(angka) {
            return 'Rp ' + (angka || 0).toLocaleString('id-ID');
        }

        const tomSelectSettings = {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Cari Jasa...",
            plugins: ['dropdown_input'],
            allowEmptyOption: true,
        };

        const productContainer = document.getElementById('product-rows-container');
        const addProductRowBtn = document.getElementById('add-product-row-btn');
        const productTemplate = document.getElementById('product-row-template');

        function setupProductRowEvents(row) {
            const productSelect = row.querySelector('.product-select');
            const kategoriInput = row.querySelector('.area-input'); // Memakai area-input untuk database
            const hargaInput = row.querySelector('.harga-input');
            const volumeInput = row.querySelector('.volume-input');
            const satuanLabel = row.querySelector('.satuan-label');
            const hiddenSatuan = row.querySelector('.hidden-satuan');
            const removeBtn = row.querySelector('.remove-row-btn');

            if (typeof TomSelect !== 'undefined' && !productSelect.tomselect) {
                new TomSelect(productSelect, tomSelectSettings);
            }

            productSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const originalOption = Array.from(this.options).find(opt => opt.value === selectedValue);
                
                if(originalOption && selectedValue !== "") {
                    hargaInput.value = originalOption.getAttribute('data-harga');
                    kategoriInput.value = originalOption.getAttribute('data-kategori');
                    const stn = originalOption.getAttribute('data-satuan');
                    satuanLabel.textContent = "("+stn+")";
                    hiddenSatuan.value = stn;
                } else {
                    hargaInput.value = 0;
                    kategoriInput.value = "";
                    satuanLabel.textContent = "";
                    hiddenSatuan.value = "";
                }
                calculateAllTotals();
            });

            hargaInput.addEventListener('input', calculateAllTotals);
            volumeInput.addEventListener('input', calculateAllTotals);

            removeBtn.addEventListener('click', function() {
                if (productSelect.tomselect) productSelect.tomselect.destroy();
                row.remove();
                calculateAllTotals();
            });
        }

        function addProductRow() {
            const clone = productTemplate.content.firstElementChild.cloneNode(true);

            clone.querySelector('.product-select').name = `produk[${productRowIndex}][nama]`;
            // area-input akan dikirim sebagai kategori pekerjaan
            clone.querySelector('.area-input').name = `produk[${productRowIndex}][kategori]`;
            clone.querySelector('.volume-input').name = `produk[${productRowIndex}][volume]`;
            clone.querySelector('.harga-input').name = `produk[${productRowIndex}][harga]`;
            // Hidden satuan akan dikirim sebagai deskripsi (karena struktur DB offer_items)
            clone.querySelector('.hidden-satuan').name = `produk[${productRowIndex}][satuan]`;

            productContainer.appendChild(clone);
            setupProductRowEvents(clone);
            productRowIndex++;
            calculateAllTotals();
        }

        function calculateAllTotals() {
            let totalJasa = 0;

            document.querySelectorAll('.product-row').forEach(row => {
                const vol = parseFloat(row.querySelector('.volume-input').value) || 0;
                const hrg = parseFloat(row.querySelector('.harga-input').value) || 0;
                const subtotal = vol * hrg;

                row.querySelector('.total-output').value = formatRupiah(subtotal);
                totalJasa += subtotal;
            });

            const diskon = parseFloat(diskonGlobalInput.value) || 0;
            let grandTotal = totalJasa - diskon;
            if(grandTotal < 0) grandTotal = 0;

            displaySubtotal.textContent = formatRupiah(totalJasa);
            displayDiskon.textContent = "- " + formatRupiah(diskon);

            if (hilangkanGrandTotal.checked) {
                totalKeseluruhanDisplay.innerHTML = '<span class="text-gray-400 text-2xl">Rp (Tersembunyi)</span>';
            } else {
                totalKeseluruhanDisplay.textContent = formatRupiah(grandTotal);
            }
        }

        addProductRowBtn.addEventListener('click', addProductRow);
        addProductRow();

        // Loader
        const offerForm = document.getElementById('offer-form');
        if (offerForm) {
            offerForm.addEventListener('submit', function() {
                const submitBtn = offerForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }
    });
</script>
@endsection