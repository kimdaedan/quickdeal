@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Histori Purchase Order (PO)</h1>
            <p class="text-gray-500 mt-1">Daftar PO yang telah dibuat berdasarkan penawaran publik.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
            {{ session('success') }}
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
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>
                            @elseif($po->status == 'approved')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Disetujui</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($po->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('modal-po-{{ $po->id }}').classList.remove('hidden')" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">Detail</button>
                            @if(auth()->user()->role !== 'client' && $po->status === 'pending')
                                <form action="{{ route('admin.po.status', $po->id) }}" method="POST" onsubmit="return confirm('Terima PO ini dan buat Invoice otomatis?');">
                                    @csrf
                                    <input type="hidden" name="status" value="receive">
                                    <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">Terima (Buat Invoice)</button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Detail PO -->
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
        @if($pos->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pos->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
