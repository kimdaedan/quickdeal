@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Penawaran Proyek/Jasa</h1>
            <p class="text-sm text-gray-500">Perbarui data penawaran di bawah ini.</p>
        </div>
        <a href="{{ route('histori.index') }}" class="text-sm text-gray-600 hover:text-blue-600 transition">
            &larr; Kembali ke Histori
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

        <form id="offer-form" action="{{ route('histori.update', $offer->id) }}" method="POST" class="p-6 md:p-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" id="action_input" value="save">

            <!-- 1. Informasi Klien -->
            <div class="mb-8 border-b border-gray-100 pb-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-md text-xs">1</span> Informasi Klien
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Klien / Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_klien" value="{{ old('nama_klien', $offer->nama_klien) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Detail Proyek (Opsional)</label>
                        <input type="text" name="client_details" value="{{ old('client_details', $offer->client_details) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                    {{-- Loop item produk yang sudah ada --}}
                    @forelse($offer->items as $index => $item)
                        @php
                            $p = \App\Models\Product::where('nama_jasa', $item->nama_produk)->first();
                            $kategori = $item->area_dinding ?: ($p->kategori ?? '');
                            $satuan = $item->deskripsi_tambahan ?: ($p->satuan ?? '');
                        @endphp
                    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end p-4 border border-gray-200 rounded-lg bg-gray-50 relative hover:shadow-sm transition-shadow">

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Jasa</label>
                            <select name="produk[{{$index}}][nama]" class="product-select w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($all_products as $product)
                                <option value="{{ $product->nama_jasa }}" 
                                        data-harga="{{ $product->harga }}" 
                                        data-kategori="{{ $product->kategori }}" 
                                        data-satuan="{{ $product->satuan }}"
                                        @if($product->nama_jasa == $item->nama_produk) selected @endif>
                                    {{ $product->nama_jasa }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Kategori</label>
                            <input type="text" name="produk[{{$index}}][kategori]" value="{{ $kategori }}" class="area-input w-full rounded-md border-gray-300 bg-white text-sm" readonly placeholder="Otomatis">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1 flex items-center justify-between">
                                <span>VOL <span class="satuan-label text-[10px] text-gray-400">{{ $satuan ? "($satuan)" : '' }}</span></span>
                            </label>
                            <input type="number" step="0.01" name="produk[{{$index}}][volume]" value="{{ $item->volume + 0 }}" class="volume-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 text-center">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Harga Satuan</label>
                            <input type="number" name="produk[{{$index}}][harga]" value="{{ $item->harga_per_m2 }}" class="harga-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Subtotal</label>
                            <input type="text" class="total-output w-full bg-gray-200 border-gray-300 rounded-md text-sm font-bold text-gray-700 cursor-not-allowed" readonly>
                            <input type="hidden" name="produk[{{$index}}][satuan]" value="{{ $satuan }}" class="hidden-satuan">
                        </div>

                        <div class="absolute top-2 right-2 md:static md:col-span-1 md:flex md:justify-end md:pb-1">
                            <button type="button" class="remove-row-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors" title="Hapus Baris">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    @endforelse
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
                            {{ $offer->hilangkan_grand_total ? 'checked' : '' }}>
                        <label for="hilangkan_grand_total" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                            Sembunyikan Grand Total di Cetakan
                        </label>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm text-gray-700 font-bold mb-1">Diskon Global / Tambahan (Rp)</label>
                        <input type="number" id="diskon_global_input" name="diskon_global" value="{{ $offer->diskon_global ?? 0 }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                <button type="submit"
                    value="save"
                    onclick="document.getElementById('action_input').value = 'save';"
                    class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all">
                    Update (Simpan Perubahan)
                </button>
                <button type="submit"
                    value="save_and_copy"
                    onclick="document.getElementById('action_input').value = 'save_and_copy';"
                    class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all">
                    Update & Copy (Buat Baru)
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
                @foreach ($all_products as $product)
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
        
        let productRowIndex = {{ max($offer->items->count(), 0) }};

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
                    // Update field value with master data
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

        // Init baris lama yang dari DB
        document.querySelectorAll('.product-row').forEach(row => {
            setupProductRowEvents(row);
        });

        function addProductRow() {
            const clone = productTemplate.content.firstElementChild.cloneNode(true);

            clone.querySelector('.product-select').name = `produk[${productRowIndex}][nama]`;
            clone.querySelector('.area-input').name = `produk[${productRowIndex}][kategori]`;
            clone.querySelector('.volume-input').name = `produk[${productRowIndex}][volume]`;
            clone.querySelector('.harga-input').name = `produk[${productRowIndex}][harga]`;
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

        calculateAllTotals();

        // Loader
        const offerForm = document.getElementById('offer-form');
        if (offerForm) {
            offerForm.addEventListener('submit', function() {
                const submitButtons = offerForm.querySelectorAll('button[type="submit"]');
                submitButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    if (btn.value === 'save') btn.innerHTML = 'Menyimpan...';
                    else if (btn.value === 'save_and_copy') btn.innerHTML = 'Menduplikasi...';
                });
            });
        }
    });
</script>
@endsection