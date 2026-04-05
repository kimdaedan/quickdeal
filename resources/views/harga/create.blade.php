@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Tambah Data Harga Baru</h1>

        {{-- Action diarahkan ke route 'harga.store' --}}
        <form action="{{ route('harga.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nama_jasa" class="block text-sm font-medium text-gray-700">Nama Jasa</label>
                    <input type="text" name="nama_jasa" id="nama_jasa" placeholder="Contoh: Pengecatan Dinding" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="kategori" id="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Pekerjaan Sipil & Struktur">Pekerjaan Sipil & Struktur</option>
                        <option value="Pekerjaan Arsitektural & Finishing (Desain Interior)">Pekerjaan Arsitektural & Finishing (Desain Interior)</option>
                        <option value="Pekerjaan Elektrikal (Kelistrikan)">Pekerjaan Elektrikal (Kelistrikan)</option>
                        <option value="Pekerjaan Mekanikal & Plumbing (MEP)">Pekerjaan Mekanikal & Plumbing (MEP)</option>
                        <option value="Pekerjaan Desain & Konsultasi">Pekerjaan Desain & Konsultasi</option>
                    </select>
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                    <select name="satuan" id="satuan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="m2">m2 (Meter Persegi)</option>
                        <option value="m1">m1 / m (Meter Lari)</option>
                        <option value="m3">m3 (Meter Kubik)</option>
                        <option value="Ls">Ls (Lump Sum)</option>
                        <option value="Titik">Titik</option>
                        <option value="Unit">Unit</option>
                        <option value="Bh">Bh (Buah) / Pcs</option>
                        <option value="Org">Org / Hok</option>
                        <option value="Jam">Jam / Hari</option>
                    </select>
                </div>

                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga Jasa (Rp)</label>
                    <input type="number" name="harga" id="harga" placeholder="15000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded hover:bg-gray-700 transition ease-in-out duration-150">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection