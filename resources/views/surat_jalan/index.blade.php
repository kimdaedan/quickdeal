@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4 flex-grow">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Histori Surat Jalan
            </h1>
        </div>

        <!-- Form Pencarian -->
        <form action="{{ route('surat_jalan.index') }}" method="GET" class="mb-4">
            <div class="flex">
                <input type="text"
                       name="search"
                       placeholder="Cari berdasarkan No. Surat atau Nama Klien..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800"
                       value="{{ $search ?? '' }}">
                <button type="submit" class="ml-2 bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition">
                    Cari
                </button>
            </div>
        </form>

        <!-- Pesan Sukses -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-visible pb-32">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal Pengiriman</th>
                        <th scope="col" class="px-6 py-3">No. Surat Jalan</th>
                        <th scope="col" class="px-6 py-3">Penerima Barang</th>
                        <th scope="col" class="px-6 py-3">Instansi / Tujuan</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($suratJalans as $sj)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $sj->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $sj->no_surat }}</td>
                        <td class="px-6 py-4">{{ $sj->penerima_nama }}</td>
                        <td class="px-6 py-4">{{ $sj->penerima_instansi }}</td>
                        <td class="px-6 py-4 text-center">

                            <!-- Dropdown Action -->
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Options
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                     style="display: none;">
                                    <div class="py-1" role="menu">
                                        <!-- Lihat Detail / Cetak -->
                                        <a href="{{ route('surat_jalan.show', $sj->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                            Lihat / Cetak
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('surat_jalan.destroy', $sj->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Surat Jalan ini? Foto dokumentasi juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Belum ada Surat Jalan yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $suratJalans->appends(['search' => $search])->links() }}
        </div>

    </div>
</div>
@endsection
