@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Histori Purchase Order (PO)</h1>
            <p class="text-gray-500 mt-1">Daftar PO yang telah dibuat berdasarkan penawaran publik.</p>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form action="{{ request()->url() }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama klien, proyek, atau kode penawaran..." 
                               class="pl-10 w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm text-gray-700 bg-white">
                        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="receive" {{ request('status') === 'receive' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm hover:shadow-md">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ request()->url() }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Advanced date filter collapse/grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm text-gray-700">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm text-gray-700">
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penawaran Ref</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama/Bisnis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pos as $po)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $po->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($po->offer)
                                <a href="{{ route('front.penawaran.show', $po->offer->id) }}" class="text-blue-600 hover:underline font-medium" target="_blank">
                                    {{ $po->offer->judul_publik ?: 'SP-'.str_pad($po->offer->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            @else
                                <span class="text-gray-400 italic">Penawaran dihapus</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                            {{ $po->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $po->phone }}<br>
                            <span class="text-xs text-gray-400">{{ $po->email }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($po->status == 'pending')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">Pending</span>
                            @elseif($po->status == 'receive' || $po->status == 'approved')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200 shadow-sm">Disetujui</span>
                            @elseif($po->status == 'rejected')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200 shadow-sm">Ditolak</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">{{ ucfirst($po->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <div>
                                    <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex justify-center items-center gap-1.5 rounded-xl border border-gray-200 shadow-sm px-4 py-2 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500 transition-all duration-200" id="menu-button-{{ $po->id }}" aria-expanded="true" aria-haspopup="true">
                                        Aksi
                                        <svg class="h-4 w-4 text-gray-500 transition-transform duration-200" :class="{'rotate-180 text-blue-500': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100" 
                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-75" 
                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                     x-transition:leave-end="transform opacity-0 scale-95" 
                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-30" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $po->id }}" tabindex="-1" style="display: none;">
                                    
                                    <div class="py-1" role="none">
                                        <!-- Detail -->
                                        <button type="button" @click="open = false; document.getElementById('modal-po-{{ $po->id }}').classList.remove('hidden')" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2.5 text-sm w-full transition text-left" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail PO
                                        </button>
                                        
                                        <!-- Cetak -->
                                        <a href="{{ route('po.print', $po->id) }}" target="_blank" @click="open = false" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2.5 text-sm transition text-left" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Cetak PO
                                        </a>

                                        <!-- Edit (Hanya Client, status pending) -->
                                        @if(auth()->user()->role === 'client' && $po->status === 'pending')
                                        <a href="{{ route('client.po.edit', $po->id) }}" @click="open = false" class="text-indigo-600 hover:bg-indigo-50 hover:text-indigo-900 group flex items-center px-4 py-2.5 text-sm transition text-left" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-indigo-500 group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit PO
                                        </a>
                                        @endif
                                    </div>

                                    @if(auth()->user()->role !== 'client' && $po->status === 'pending')
                                    <div class="py-1" role="none">
                                        <!-- Setuju PO -->
                                        <form action="{{ route('admin.po.status', $po->id) }}" method="POST" onsubmit="return confirm('Setujui PO ini dan buat Invoice otomatis?');">
                                            @csrf
                                            <input type="hidden" name="status" value="receive">
                                            <button type="submit" @click="open = false" class="text-green-600 hover:bg-green-50 hover:text-green-900 group flex items-center px-4 py-2.5 text-sm w-full transition text-left" role="menuitem">
                                                <svg class="mr-3 h-5 w-5 text-green-500 group-hover:text-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Setuju PO
                                            </button>
                                        </form>

                                        <!-- Tolak PO -->
                                        <form action="{{ route('admin.po.status', $po->id) }}" method="POST" onsubmit="return confirm('Tolak PO ini?');">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" @click="open = false" class="text-red-600 hover:bg-red-50 hover:text-red-900 group flex items-center px-4 py-2.5 text-sm w-full transition text-left" role="menuitem">
                                                <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Tolak PO
                                            </button>
                                        </form>
                                    </div>
                                    @endif

                                    @if(auth()->user()->role !== 'client' && ($po->status === 'receive' || $po->status === 'approved') && $po->invoice)
                                    <div class="py-1" role="none">
                                        <!-- Lihat Invoice -->
                                        <a href="{{ route('invoice.show', $po->invoice->id) }}" @click="open = false" class="text-emerald-600 hover:bg-emerald-50 hover:text-emerald-900 group flex items-center px-4 py-2.5 text-sm transition text-left" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-emerald-500 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Lihat Invoice
                                        </a>
                                    </div>
                                    @endif

                                    <div class="py-1" role="none">
                                        <!-- Hapus PO (Client only for pending PO, Admin for all POs) -->
                                        @if(auth()->user()->role !== 'client' || $po->status === 'pending')
                                        <form action="{{ route('po.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" @click="open = false" class="text-red-600 hover:bg-red-50 hover:text-red-900 group flex items-center px-4 py-2.5 text-sm w-full transition text-left font-semibold" role="menuitem">
                                                <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus PO
                                            </button>
                                        </form>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <p class="text-lg font-medium text-gray-900">Belum ada Histori PO</p>
                                <p class="text-sm text-gray-500">Data purchase order akan muncul di sini setelah dibuat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Modals for each PO -->
        @foreach($pos as $po)
        <div id="modal-po-{{ $po->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-po-{{ $po->id }}').classList.add('hidden')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                    Detail Purchase Order
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Nama / Bisnis</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $po->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Tanggal Dibuat</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $po->created_at->format('d F Y, H:i') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Email</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $po->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">No. HP / Telepon</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $po->phone }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Penawaran Referensi</p>
                                        <p class="text-sm font-medium text-blue-600">
                                            @if($po->offer)
                                                <a href="{{ route('front.penawaran.show', $po->offer->id) }}" target="_blank" class="hover:underline">{{ $po->offer->judul_publik ?: 'SP-'.str_pad($po->offer->id, 4, '0', STR_PAD_LEFT) }}</a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Detail Project / Kebutuhan</p>
                                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $po->detail_project }}</p>
                                    </div>

                                    @if($po->alamat_detail)
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mt-3">
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Alamat Detail (Pengerjaan)</p>
                                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $po->alamat_detail }}</p>
                                    </div>
                                    @endif

                                    @if($po->offer && $po->offer->jenis_penawaran === 'produk' && $po->custom_quantities)
                                    <div class="mt-4">
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Produk yang Dipesan (Custom Qty)</p>
                                        <div class="overflow-x-auto border rounded-lg border-gray-200">
                                            <table class="w-full text-left text-sm border-collapse">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-3 py-2 border-b font-medium text-gray-700">Produk</th>
                                                        <th class="px-3 py-2 border-b font-medium text-gray-700 text-center">Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($po->offer->items as $item)
                                                        @if(isset($po->custom_quantities[$item->id]))
                                                        <tr class="border-b">
                                                            <td class="px-3 py-2 text-gray-800">{{ $item->nama_produk }}</td>
                                                            <td class="px-3 py-2 text-center font-semibold text-gray-900">{{ $po->custom_quantities[$item->id] }}</td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($po->custom_total)
                                        <p class="text-right mt-2 text-sm font-bold text-gray-900">Total: Rp {{ number_format($po->custom_total, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="button" onclick="document.getElementById('modal-po-{{ $po->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if($pos->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pos->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
