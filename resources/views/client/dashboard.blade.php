@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- 1. WELCOME BANNER --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl shadow-xl overflow-hidden mb-8 border border-slate-800 relative">
        {{-- Decorative SVG backgrounds --}}
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-56 h-56 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="p-8 md:p-10 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/15 border border-blue-500/35 text-xs text-blue-400 font-semibold mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                    Portal Klien Quick.Deal
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-slate-300 text-sm md:text-base max-w-xl leading-relaxed">
                    Selamat datang di panel khusus klien Anda. Pantau status penawaran, kelola tagihan pembayaran, dan kelola dokumen pesanan dengan mudah dalam satu tempat.
                </p>
            </div>
            
            {{-- Account Quick Info --}}
            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 text-xs text-slate-300 space-y-2.5 self-start md:self-auto min-w-[240px]">
                <div class="font-bold text-white uppercase tracking-wider border-b border-white/10 pb-2 mb-2">Informasi Akun</div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">👤</span>
                    <span>Username: <strong>{{ Auth::user()->username }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">📧</span>
                    <span>Email: <strong>{{ Auth::user()->email }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">📞</span>
                    <span>Telepon: <strong>{{ Auth::user()->no_telp ?? 'Belum diatur' }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        {{-- Total Tagihan Belum Terbayar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 border-l-4 border-rose-500 p-6 flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Tagihan Belum Terbayar</p>
                <h3 class="text-2xl font-extrabold text-slate-900">
                    Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-500 mt-2">
                    @if($unpaidCount > 0)
                        Menunggak pembayaran untuk <strong class="text-rose-600">{{ $unpaidCount }} invoice</strong>.
                    @else
                        Semua tagihan Anda telah lunas sepenuhnya. Terima kasih!
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        {{-- Invoice Belum Lunas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 border-l-4 border-amber-500 p-6 flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Invoice Pending / Belum Lunas</p>
                <h3 class="text-2xl font-extrabold text-slate-900">
                    {{ $unpaidCount }} <span class="text-sm font-medium text-gray-500">Invoice</span>
                </h3>
                <p class="text-xs text-gray-500 mt-2">
                    Menunggu proses transfer atau verifikasi admin.
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        {{-- Purchase Order --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 border-l-4 border-blue-500 p-6 flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Purchase Order (PO)</p>
                <h3 class="text-2xl font-extrabold text-slate-900">
                    {{ $totalPOsCount }} <span class="text-sm font-medium text-gray-500">Diajukan</span>
                </h3>
                <p class="text-xs text-gray-500 mt-2">
                    @if($pendingPOsCount > 0)
                        Ada <strong class="text-blue-600">{{ $pendingPOsCount }} PO</strong> masih berstatus pending persetujuan.
                    @else
                        Semua Purchase Order telah diproses.
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
        </div>

    </div>

    {{-- 3. DATA LISTS (INVOICES & PURCHASE ORDERS) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

        {{-- INVOICES LIST --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            📂
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Invoice Pembayaran Anda</h3>
                            <p class="text-[11px] text-gray-500">Histori 5 transaksi tagihan terbaru Anda</p>
                        </div>
                    </div>
                    <a href="{{ route('invoice.histori') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-gray-100 overflow-x-auto">
                    @forelse($recentInvoices as $invoice)
                        @php
                            $hasPendingVerification = $invoice->payments()->where('status_verifikasi', 'pending')->exists();
                            $isOverdue = $invoice->status !== 'paid' && $invoice->created_at < now()->subMonth();
                        @endphp
                        <div class="p-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-800">{{ $invoice->no_invoice }}</span>
                                    
                                    {{-- Status Badge --}}
                                    @if ($invoice->status === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-150">Lunas</span>
                                    @elseif ($hasPendingVerification)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-150">Menunggu Verifikasi</span>
                                    @elseif ($isOverdue)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-150">Jatuh Tempo</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-150">Belum Lunas</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-400">Dibuat: {{ $invoice->created_at->format('d M Y') }}</p>
                            </div>
                            
                            <div class="text-right space-y-1">
                                <div class="text-xs font-extrabold text-slate-800">
                                    Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                                </div>
                                @if($invoice->status !== 'paid' && $invoice->sisa_pembayaran > 0)
                                    <div class="text-[10px] text-rose-600">
                                        Sisa: Rp {{ number_format($invoice->sisa_pembayaran, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <a href="{{ route('invoice.show', $invoice->id) }}" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-slate-900 flex items-center justify-center transition" title="Lihat Invoice">
                                    👁️
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 text-xs">
                            Belum ada invoice yang terdaftar untuk akun Anda.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- PURCHASE ORDERS LIST --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                            📋
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Purchase Order (PO) Terbaru</h3>
                            <p class="text-[11px] text-gray-500">Daftar 5 pengajuan pesanan jasa/produk Anda</p>
                        </div>
                    </div>
                    <a href="{{ route('client.po.history') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-gray-100 overflow-x-auto">
                    @forelse($recentPOs as $po)
                        <div class="p-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-gray-800 truncate max-w-[180px]">{{ $po->detail_project }}</p>
                                <div class="flex items-center gap-2 text-[10px]">
                                    <span class="text-gray-400">{{ $po->created_at->format('d M Y') }}</span>
                                    
                                    {{-- PO Status --}}
                                    @if ($po->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                    @elseif ($po->status === 'receive' || $po->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('po.print', $po->id) }}" target="_blank" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-slate-900 flex items-center justify-center transition" title="Cetak PO">
                                    🖨️
                                </a>
                                @if($po->status === 'pending')
                                    <a href="{{ route('client.po.edit', $po->id) }}" class="p-1.5 hover:bg-slate-100 rounded-lg text-blue-500 hover:text-blue-700 flex items-center justify-center transition" title="Ubah PO">
                                        ✏️
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 text-xs">
                            Anda belum pernah mengajukan Purchase Order.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- 4. QUICK ACTIONS CARD GRID --}}
    <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Akses Cepat Layanan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Penawaran Publik --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg mb-4">
                        🔍
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Tinjau Penawaran Publik</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        Lihat portofolio penawaran jasa dan produk dari kami. Ajukan negosiasi harga terbaik langsung dari halaman penawaran.
                    </p>
                </div>
                <a href="{{ route('front.penawaran.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1 mt-2">
                    Lihat Penawaran Publik &rarr;
                </a>
            </div>

            {{-- Pengaturan Akun --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center text-lg mb-4">
                        ⚙️
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Pengaturan Profil & Akun</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        Kelola data kontak aktif Anda, ganti alamat email, ubah nomor telepon/WhatsApp, atau perbarui kata sandi akun Anda secara aman.
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-slate-700 hover:text-slate-900 inline-flex items-center gap-1 mt-2">
                    Buka Pengaturan Akun &rarr;
                </a>
            </div>

            {{-- Hubungi Bantuan --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg mb-4">
                        💬
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Pusat Hubungi Bantuan</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        Butuh klarifikasi terkait invoice, memiliki kendala pengerjaan proyek, atau ingin berkonsultasi mengenai pesanan berjalan? Hubungi kami langsung.
                    </p>
                </div>
                <a href="mailto:support@quickdeal.com" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 inline-flex items-center gap-1 mt-2">
                    Kirim Email ke Support &rarr;
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
