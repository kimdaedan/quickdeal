@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Title & Header --}}
    <div class="mb-10 pb-6 border-b border-gray-200">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Akun</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui data profil pribadi, nomor kontak, serta kata sandi keamanan Anda.</p>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
    <div class="max-w-7xl mx-auto mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-800 text-sm shadow-sm flex justify-between items-center" role="alert">
        <div class="flex items-center gap-2">
            <span class="text-base">✅</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
    </div>
    @endif

    <div class="space-y-12">
        
        {{-- SECTION 1: INFORMASI PROFIL --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Left column: Description --}}
            <div class="md:col-span-1">
                <h3 class="text-lg font-bold text-gray-900">Informasi Profil</h3>
                <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                    Perbarui informasi dasar profil akun Anda. Nama, username, dan alamat email Anda harus valid agar sistem dapat berjalan dengan baik.
                </p>
                <div class="mt-4 p-4 bg-blue-50/60 rounded-2xl border border-blue-100 text-[11px] text-blue-900 leading-relaxed">
                    <strong>💡 Info Kontak:</strong> Nomor telepon/WhatsApp Anda akan digunakan oleh klien untuk menghubungi Anda terkait pesanan/penawaran.
                </div>
            </div>

            {{-- Right column: Form Card --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="p-6 md:p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama --}}
                                <div>
                                    <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            👤
                                        </div>
                                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" 
                                               class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                    </div>
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Username --}}
                                <div>
                                    <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            🏷️
                                        </div>
                                        <input type="text" name="username" id="username" required value="{{ old('username', $user->username) }}" 
                                               class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                    </div>
                                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            📧
                                        </div>
                                        <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}" 
                                               class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                    </div>
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- No Telp --}}
                                <div>
                                    <label for="no_telp" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            📞
                                        </div>
                                        <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $user->no_telp) }}" 
                                               class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" 
                                               placeholder="Contoh: 0812-3456-7890">
                                    </div>
                                    @error('no_telp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition duration-200 text-xs uppercase tracking-wider shadow-sm hover:shadow">
                                Simpan Informasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <hr class="border-gray-200">

        {{-- SECTION 2: UBAH PASSWORD --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Left column: Description --}}
            <div class="md:col-span-1">
                <h3 class="text-lg font-bold text-gray-900">Ubah Kata Sandi</h3>
                <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                    Pastikan akun Anda menggunakan kata sandi yang panjang, unik, dan aman untuk menghindari risiko keamanan akun.
                </p>
                <p class="text-xs text-slate-400 mt-3 italic">
                    Biarkan kolom di samping kosong jika Anda tidak ingin merubah sandi login Anda.
                </p>
            </div>

            {{-- Right column: Form Card --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Hidden inputs to keep profile data intact --}}
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="username" value="{{ $user->username }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="no_telp" value="{{ $user->no_telp }}">

                        <div class="p-6 md:p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Password --}}
                                <div>
                                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            🔒
                                        </div>
                                        <input type="password" name="password" id="password" 
                                               class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" 
                                               placeholder="Minimal 6 karakter">
                                    </div>
                                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi Baru</label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            🔒
                                        </div>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" 
                                               placeholder="Ketik ulang kata sandi">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100">
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl transition duration-200 text-xs uppercase tracking-wider shadow-sm hover:shadow">
                                Perbarui Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
