@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-5xl mx-auto"> {{-- Diperlebar sedikit dari max-w-4xl agar tabel lebih lega --}}

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Daftar Harga Jasa
            </h1>
            <a href="{{ url('/daftar-harga/tambah') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition ease-in-out duration-150">
                + Tambah Baru
            </a>
        </div>

        {{-- Tambahkan overflow-x-auto agar responsif di layar kecil --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 min-w-[800px]"> {{-- min-w ditambahkan agar tabel tidak tergencet --}}
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nama Jasa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Satuan
                        </th>
                        <th scope="col" class="px-6 py-3 text-right">
                            Harga
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        {{-- Nama Jasa --}}
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900">
                            {{ $product->nama_jasa }}
                        </th>

                        {{-- Kategori --}}
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-300">
                                {{ $product->kategori }}
                            </span>
                        </td>

                        {{-- Satuan --}}
                        <td class="px-6 py-4 text-center">
                            {{ $product->satuan }}
                        </td>

                        {{-- Harga --}}
                        <td class="px-6 py-4 font-semibold text-right whitespace-nowrap text-gray-900">
                            Rp {{ number_format($product->harga, 0, ',', '.') }} <span class="text-gray-500 font-normal text-xs">/{{ strtolower($product->satuan) }}</span>
                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center items-center gap-3">
                               <a href="{{ route('harga.edit', $product->id) }}" class="font-medium text-blue-600 hover:text-blue-800 transition-colors">Edit</a>

                                <form action="{{ route('harga.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-800 transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                            Belum ada data produk atau jasa yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection