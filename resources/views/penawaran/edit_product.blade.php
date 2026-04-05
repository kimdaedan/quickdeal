@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Penawaran Produk</h1>
            <a href="{{ route('histori.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>

        {{-- FORM UPDATE --}}
        <form id="product-offer-form" action="{{ route('penawaran.update_product', $offer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <fieldset class="border-t pt-6">
                <legend class="text-lg font-semibold text-gray-700 px-2">1. Informasi Klien</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Klien/Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_klien" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ $offer->nama_klien }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Detail / Alamat</label>
                        <input type="text" name="client_details" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ $offer->client_details }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Penawaran</label>
                        <input type="date" name="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $offer->created_at->format('Y-m-d') }}">
                    </div>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">2. Daftar Produk</legend>

                <datalist id="list-produk-db">
                    @foreach ($products as $product)
                        <option value="{{ $product->nama_jasa }}" data-harga="{{ $product->harga }}">
                    @endforeach
                </datalist>

                <div id="items-container" class="space-y-4 mt-4">
                </div>

                <div class="mt-4">
                    <button type="button" id="add-row-btn" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2 border border-blue-600 border-dashed w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Baris Produk
                    </button>
                </div>
            </fieldset>

            <div class="mt-8 pt-6 border-t grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-4 rounded-lg h-fit">
                    <h4 class="font-semibold text-gray-700 mb-3">Opsi Dokumen</h4>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input name="hilangkan_grand_total" type="checkbox" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded cursor-pointer" {{ $offer->hilangkan_grand_total ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-gray-700 cursor-pointer">Hilangkan Grand Total</label>
                            <p class="text-gray-500 text-xs">Sembunyikan baris Grand Total di surat penawaran.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-6 rounded-lg flex flex-col gap-3">
                    <div class="flex justify-between text-gray-600 text-sm"><span>Total QTY:</span><span id="display-total-qty" class="font-semibold">0</span></div>
                    <div class="flex justify-between text-gray-700 font-medium"><span>Total Harga Produk:</span><span id="display-subtotal">Rp 0</span></div>

                    <div class="flex justify-between items-center text-red-600">
                        <span class="text-sm font-semibold">Diskon Global/Tambahan:</span>
                        <div class="flex items-center gap-1 w-1/2 justify-end">
                            <span class="text-xs font-bold">- Rp</span>
                            <input type="number" name="diskon_global" id="global-discount-input"
                                   class="w-full text-right text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 p-1"
                                   value="{{ $offer->diskon_global ?? 0 }}" min="0">
                        </div>
                    </div>

                    <hr class="border-gray-300 my-2">
                    <div class="flex justify-between text-gray-900 text-xl block">
                        <span class="font-bold">Grand Total:</span>
                        <span id="display-grand-total" class="font-extrabold text-blue-800 text-3xl">Rp 0</span>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full bg-yellow-600 text-white font-bold py-4 px-6 rounded-lg hover:bg-yellow-700 transition shadow-lg text-lg flex items-center justify-center gap-2">
                            Update Penawaran Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="row-template">
    <div class="item-row bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm relative mb-5">
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
            <div class="md:col-span-8">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Produk</label>
                <input type="text" class="nama-produk-input w-full rounded-md border-gray-300 text-base py-2 px-3 shadow-inner" list="list-produk-db" placeholder="Ketik..." autocomplete="off">
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan (Opsional)</label>
                <input type="text" class="keterangan-input w-full rounded-md border-gray-300 text-sm py-2 px-3" placeholder="Warna, Ukuran, dll..." autocomplete="off">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Harga (@)</label>
                <input type="number" class="harga-input w-full rounded-md border-gray-300 text-sm text-right font-medium" placeholder="0">
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">QTY</label>
                <input type="number" step="0.01" class="qty-input w-full rounded-md border-gray-300 text-sm text-center font-bold" value="1" min="1">
            </div>
            
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-red-500 uppercase mb-1 flex justify-between">
                    <span>Diskon <span class="text-[10px] text-gray-400 font-normal">(Rp)</span></span>
                </label>
                <input type="number" class="diskon-input w-full rounded-md border-red-200 text-sm text-right text-red-600 placeholder-red-200" placeholder="0">
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end items-center gap-4">
            <label class="block text-xs font-semibold text-gray-500 uppercase">Subtotal</label>
            <input type="text" class="subtotal-input w-1/2 md:w-1/3 rounded-md border-transparent bg-gray-200 text-xl text-right font-extrabold text-gray-900" readonly>
        </div>

        <button type="button" class="remove-btn absolute -top-3 -right-3 bg-red-500 text-white rounded-full p-1.5 shadow-md hover:bg-red-600 hover:scale-110 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const existingItems = @json($offer->items);

        const container = document.getElementById('items-container');
        const template = document.getElementById('row-template').content.firstElementChild;
        const displayTotalQty = document.getElementById('display-total-qty');
        const displaySubtotal = document.getElementById('display-subtotal');
        const displayGrandTotal = document.getElementById('display-grand-total');
        const globalDiscountInput = document.getElementById('global-discount-input');

        let rowIndex = 0;

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        };

        function addRow(data = null) {
            const newRow = template.cloneNode(true);

            newRow.querySelector('.nama-produk-input').name = `items[${rowIndex}][nama_produk]`;
            newRow.querySelector('.keterangan-input').name = `items[${rowIndex}][keterangan]`;
            newRow.querySelector('.harga-input').name = `items[${rowIndex}][harga_satuan]`;
            newRow.querySelector('.qty-input').name = `items[${rowIndex}][qty]`;
            newRow.querySelector('.diskon-input').name = `items[${rowIndex}][diskon]`;

            if(data) {
                newRow.querySelector('.nama-produk-input').value = data.nama_produk;
                newRow.querySelector('.harga-input').value = data.harga_per_m2;
                newRow.querySelector('.qty-input').value = data.volume;

                let diskon = 0;
                let keterangan = data.deskripsi_tambahan || '';
                
                if(keterangan.includes('Diskon/Item: Rp')) {
                    let match = keterangan.match(/Diskon\/Item: Rp ([0-9,.]+)/);
                    if(match) {
                        diskon = parseInt(match[1].replace(/[.,]/g, ''));
                        keterangan = keterangan.replace(match[0], ''); // hilangkan string diskon dari keterangan
                        keterangan = keterangan.replace(/ \| $/, ''); // hapus trailing pipe kalau ada
                    }
                }
                
                newRow.querySelector('.keterangan-input').value = keterangan.trim();

                if(diskon > 0) newRow.querySelector('.diskon-input').value = diskon;
            }

            container.appendChild(newRow);
            initRowEvents(newRow);
            rowIndex++;
        }

        function initRowEvents(row) {
            const inputs = row.querySelectorAll('input');
            const removeBtn = row.querySelector('.remove-btn');
            const nameInput = row.querySelector('.nama-produk-input');
            const hargaInput = row.querySelector('.harga-input');

            nameInput.addEventListener('input', function() {
                const val = this.value;
                const options = document.getElementById('list-produk-db').options;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value === val) {
                        if(hargaInput.value == "" || hargaInput.value == 0) {
                            hargaInput.value = options[i].getAttribute('data-harga');
                        }
                        calculateAll();
                        break;
                    }
                }
            });

            inputs.forEach(input => input.addEventListener('input', calculateAll));
            removeBtn.addEventListener('click', function() { row.remove(); calculateAll(); });
        }

        function calculateAll() {
            let totalQty = 0;
            let totalHargaProduk = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const diskonItem = parseFloat(row.querySelector('.diskon-input').value) || 0;

                let subtotalBaris = (harga * qty) - diskonItem;
                if(subtotalBaris < 0) subtotalBaris = 0;

                row.querySelector('.subtotal-input').value = formatRupiah(subtotalBaris);
                totalQty += qty;
                totalHargaProduk += subtotalBaris;
            });

            const diskonGlobal = parseFloat(globalDiscountInput.value) || 0;
            let grandTotal = totalHargaProduk - diskonGlobal;
            if(grandTotal < 0) grandTotal = 0;

            displayTotalQty.textContent = totalQty;
            displaySubtotal.textContent = formatRupiah(totalHargaProduk);
            displayGrandTotal.textContent = formatRupiah(grandTotal);
        }

        globalDiscountInput.addEventListener('input', calculateAll);
        document.getElementById('add-row-btn').addEventListener('click', () => addRow(null));

        if (existingItems && existingItems.length > 0) {
            existingItems.forEach(item => addRow(item));
        } else {
            addRow(null);
        }

        // Anti Double Submit
        const form = document.getElementById('product-offer-form');
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = 'Memperbarui...';
        });

        setTimeout(calculateAll, 100);
    });
</script>
@endsection