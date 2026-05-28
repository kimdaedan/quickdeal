@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">

    <div class="max-w-4xl mx-auto mb-4 flex justify-end gap-2 print:hidden">
        <a href="{{ route('invoice.histori') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300">
            &larr; Kembali ke Histori
        </a>
        <a href="{{ route('invoice.print', $invoice->id) }}" target="_blank" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors shadow-sm inline-flex items-center gap-2">
            🖨️ Print Invoice (PDF)
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg border" id="invoice-print-area">

        {{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6">
            <div class="w-full">
                {{-- Menggunakan class w-full agar gambar memenuhi lebar kontainer --}}
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="w-full h-auto">
            </div>
            {{-- Garis merah di bawah tetap dipertahankan atau dihapus sesuai keinginan --}}
            <div class="w-full border-b-[4px] border-[#d32f2f] mt-1"></div>
        </header>

        <section class="mt-8 flex justify-between text-sm sans">
            <div class="w-1/2">
                <p class="font-bold mb-1">TO:</p>
                <p class="font-bold text-lg uppercase">{{ $invoice->nama_klien }}</p>

                @if($invoice->purchaseOrder && $invoice->purchaseOrder->alamat_detail)
                <p class="text-gray-700">{{ $invoice->purchaseOrder->alamat_detail }}</p>
                @elseif($invoice->offer && $invoice->offer->client_details)
                <p class="text-gray-700">{{ $invoice->offer->client_details }}</p>
                @endif

                <p class="mt-4 font-bold">Attn:</p>
                <p>{{ $invoice->nama_klien }}</p>
            </div>
            <div class="w-1/2 text-right">
                <div class="flex justify-end mb-1">
                    <span class="w-24 text-left font-bold">Tanggal</span>
                    <span class="text-left">: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</span>
                </div>

                {{-- NO INVOICE SESUAI DATABASE (KONSISTEN DENGAN HISTORI) --}}
                <div class="flex justify-end">
                    <span class="w-24 text-left font-bold">Invoice No.</span>
                    <span class="text-left">: {{ $invoice->no_invoice }}</span>
                </div>
            </div>
        </section>

        <section class="mt-8 text-sm">
            <p class="mb-2">Bersama ini kami sampaikan tagihan untuk:</p>
            <p><span class="font-medium w-20 inline-block">Project</span>: Supply Produk/Jasa</p>
            @if($invoice->purchaseOrder && $invoice->purchaseOrder->alamat_detail)
            <p><span class="font-medium w-20 inline-block">Alamat</span>: {{ $invoice->purchaseOrder->alamat_detail }}</p>
            @elseif($invoice->offer && $invoice->offer->client_details)
            <p><span class="font-medium w-20 inline-block">Alamat</span>: {{ $invoice->offer->client_details }}</p>
            @endif

            <table class="w-full mt-4 border-collapse border border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black p-2 text-left font-semibold">No.</th>
                        <th class="border border-black p-2 text-left font-semibold">Keterangan</th>
                        <th class="border border-black p-2 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-2 text-center">1</td>
                        <td class="border border-black p-2">Total Produk/Jasa (sesuai Penawaran)</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->total_penawaran, 0, ',', '.') }}</td>
                    </tr>

                    @foreach($invoice->additions as $index => $addition)
                    <tr>
                        <td class="border border-black p-2 text-center">{{ $index + 2 }}</td>
                        <td class="border border-black p-2">{{ $addition->nama_pekerjaan }}</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($addition->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach

                    <tr>
                        <td class="border border-black p-2 h-8"></td>
                        <td class="border border-black p-2"></td>
                        <td class="border border-black p-2"></td>
                    </tr>

                    <tr class="font-medium">
                        <td colspan="2" class="border border-black p-2 text-right">TOTAL</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->total_penawaran + $invoice->total_tambahan, 0, ',', '.') }}</td>
                    </tr>

                    @if($invoice->diskon > 0)
                    <tr class="font-medium">
                        <td colspan="2" class="border border-black p-2 text-right">Diskon</td>
                        <td class="border border-black p-2 text-right text-red-600">- Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</td>
                    </tr>
                    @endif

                    <tr class="font-bold bg-gray-100">
                        <td colspan="2" class="border border-black p-2 text-right">TOTAL TAGIHAN</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>

                    @foreach($invoice->payments as $payment)
                    <tr class="font-medium text-gray-600 {{ $payment->status_verifikasi === 'pending' ? 'bg-amber-50/50' : '' }}">
                        <td colspan="2" class="border border-black p-2 text-right">
                            <div class="flex flex-col items-end">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($payment->status_verifikasi === 'pending')
                                        <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                            Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            Terverifikasi
                                        </span>
                                    @endif
                                    <span class="font-bold text-gray-800">{{ $payment->keterangan }}</span>
                                </div>
                                
                                @if($payment->bukti_transfer)
                                @php
                                    $urlBukti = str_starts_with($payment->bukti_transfer, 'bukti_transfer/') ? asset($payment->bukti_transfer) : asset('storage/' . $payment->bukti_transfer);
                                @endphp
                                <a href="{{ $urlBukti }}" target="_blank" class="text-xs text-blue-600 hover:underline mt-1 print:hidden flex items-center gap-1 font-semibold">
                                    📸 Lihat Bukti Transfer
                                </a>
                                <span class="hidden print:inline text-[10px] text-gray-500 mt-0.5 font-normal">
                                    (Bukti transfer dilampirkan)
                                </span>
                                @endif

                                {{-- Form Verifikasi & Tolak Pembayaran Khusus Admin --}}
                                @if($payment->status_verifikasi === 'pending' && auth()->user()->role !== 'client')
                                <div class="mt-3 p-3 bg-white border border-amber-200 rounded-md shadow-sm w-full max-w-md text-left print:hidden">
                                    <p class="text-xs font-bold text-gray-700 mb-2">Tindakan Admin:</p>
                                    <form action="{{ route('invoice.verify_payment', $payment->id) }}" method="POST" class="flex flex-wrap gap-2 items-center">
                                        @csrf
                                        <div class="relative rounded-md shadow-sm w-36">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-xs">Rp</span>
                                            </div>
                                            <input type="number" name="jumlah" required max="{{ $invoice->sisa_pembayaran }}" placeholder="Nominal" class="block w-full pl-7 pr-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded text-xs font-bold transition">
                                            Setujui
                                        </button>
                                    </form>
                                    <div class="mt-2 pt-2 border-t border-gray-100 flex justify-end">
                                        <form action="{{ route('invoice.reject_payment', $payment->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak dan menghapus bukti transfer ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold flex items-center gap-0.5 transition">
                                                ❌ Tolak Bukti Transfer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="border border-black p-2 text-right text-sm">
                            @if($payment->status_verifikasi === 'pending')
                                <span class="text-amber-600 italic text-xs">Menunggu Verifikasi</span>
                            @else
                                <span class="text-green-600 font-bold">- Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    <tr class="font-bold text-xl bg-gray-200">
                        <td colspan="2" class="border border-black p-2 text-right">SISA PEMBAYARAN</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->sisa_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="mt-12 flex justify-between text-sm">
            <div class="text-center">
                <p>Hormat kami,</p>
                <p>CV. DAEDAN ENTERPRISE</p>
                <div class="h-28 w-48 relative">
                    <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-28 opacity-100 mx-auto">
                </div>
                <p class="font-bold text-gray-800">DIDAN SIRODJUDDIN</p>
                <p class="text-gray-600">President Director</p>
            </div>
            <div class="text-left">
                <p class="font-medium">Pembayaran melalui Bank:</p>
                <p>a/n CV. DAEDAN ENTERPRISE</p>
                <p>Bank BCA Cab. Batu Aji</p>
                <p>Rek. No. 8550 692 130</p>
            </div>
        </section>

    </div>
</div>

<style>
    @media print {
        .print\:hidden {
            display: none;
        }

        body * {
            visibility: hidden;
        }

        #invoice-print-area,
        #invoice-print-area * {
            visibility: visible;
        }

        #invoice-print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
            margin: 0;
            padding: 0.5in;
            font-size: 10pt;
        }

        /* Hindari page-break di dalam tabel */
        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
    }
</style>
@endsection