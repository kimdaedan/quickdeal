@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Data Harga</h1>

        <form action="{{ route('harga.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nama_jasa" class="block text-sm font-medium text-gray-700">Nama Jasa</label>
                    <input type="text" name="nama_jasa" id="nama_jasa" value="{{ $product->nama_jasa }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="kategori" id="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Pekerjaan Sipil & Struktur" {{ $product->kategori == 'Pekerjaan Sipil & Struktur' ? 'selected' : '' }}>Pekerjaan Sipil & Struktur</option>
                        <option value="Pekerjaan Arsitektural & Finishing (Desain Interior)" {{ $product->kategori == 'Pekerjaan Arsitektural & Finishing (Desain Interior)' ? 'selected' : '' }}>Pekerjaan Arsitektural & Finishing (Desain Interior)</option>
                        <option value="Pekerjaan Elektrikal (Kelistrikan)" {{ $product->kategori == 'Pekerjaan Elektrikal (Kelistrikan)' ? 'selected' : '' }}>Pekerjaan Elektrikal (Kelistrikan)</option>
                        <option value="Pekerjaan Mekanikal & Plumbing (MEP)" {{ $product->kategori == 'Pekerjaan Mekanikal & Plumbing (MEP)' ? 'selected' : '' }}>Pekerjaan Mekanikal & Plumbing (MEP)</option>
                        <option value="Pekerjaan Desain & Konsultasi" {{ $product->kategori == 'Pekerjaan Desain & Konsultasi' ? 'selected' : '' }}>Pekerjaan Desain & Konsultasi</option>
                    </select>
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                    <select name="satuan" id="satuan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="m2" {{ $product->satuan == 'm2' ? 'selected' : '' }}>m2 (Meter Persegi)</option>
                        <option value="m1" {{ $product->satuan == 'm1' ? 'selected' : '' }}>m1 / m (Meter Lari)</option>
                        <option value="m3" {{ $product->satuan == 'm3' ? 'selected' : '' }}>m3 (Meter Kubik)</option>
                        <option value="Ls" {{ $product->satuan == 'Ls' ? 'selected' : '' }}>Ls (Lump Sum)</option>
                        <option value="Titik" {{ $product->satuan == 'Titik' ? 'selected' : '' }}>Titik</option>
                        <option value="Unit" {{ $product->satuan == 'Unit' ? 'selected' : '' }}>Unit</option>
                        <option value="Bh" {{ $product->satuan == 'Bh' ? 'selected' : '' }}>Bh (Buah) / Pcs</option>
                        <option value="Org" {{ $product->satuan == 'Org' ? 'selected' : '' }}>Org / Hok</option>
                        <option value="Jam" {{ $product->satuan == 'Jam' ? 'selected' : '' }}>Jam / Hari</option>
                    </select>
                </div>

                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" value="{{ $product->harga }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded hover:bg-gray-700 transition ease-in-out duration-150">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection