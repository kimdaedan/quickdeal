@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
                <h1 class="text-3xl font-extrabold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="text-blue-100 text-lg">Ini adalah halaman dashboard khusus klien (Customer Portal).</p>
            </div>
            <div class="p-8">
                <p class="text-gray-700 text-lg mb-6">
                    Melalui dashboard ini, Anda dapat memantau penawaran, invoice, dan status proyek yang sedang berjalan.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kartu Info 1 -->
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Penawaran Saya</h3>
                        <p class="text-gray-600 text-sm">Lihat daftar penawaran yang telah kami buatkan untuk Anda.</p>
                        <a href="{{ route('front.penawaran.index') }}" class="mt-4 inline-block text-blue-600 font-semibold hover:text-blue-800">Lihat Penawaran &rarr;</a>
                    </div>

                    <!-- Kartu Info 2 -->
                    <div class="bg-green-50 rounded-xl p-6 border border-green-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Tagihan & Invoice</h3>
                        <p class="text-gray-600 text-sm">Pantau tagihan dan riwayat pembayaran Anda.</p>
                        <span class="mt-4 inline-block text-gray-400 font-medium cursor-not-allowed">Segera Hadir</span>
                    </div>

                    <!-- Kartu Info 3 -->
                    <div class="bg-purple-50 rounded-xl p-6 border border-purple-100 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4 text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Bantuan & Dukungan</h3>
                        <p class="text-gray-600 text-sm">Hubungi tim kami jika ada pertanyaan atau kendala.</p>
                        <a href="#" class="mt-4 inline-block text-purple-600 font-semibold hover:text-purple-800">Hubungi Kami &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
