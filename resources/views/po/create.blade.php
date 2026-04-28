@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Buat Purchase Order (PO)</h1>
            <p class="text-sm text-gray-500 mt-1">Penawaran: <span class="font-semibold text-blue-600">{{ $offer->judul_publik ?: 'SP-'.str_pad($offer->id, 4, '0', STR_PAD_LEFT) }}</span></p>
        </div>

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('po.store', $offer->id) }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Name / Business Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name ?? '') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div class="mb-5">
                <label for="detail_project" class="block text-sm font-semibold text-gray-700 mb-1">Detail Project</label>
                <textarea name="detail_project" id="detail_project" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-400"
                          placeholder="Jelaskan secara detail mengenai kebutuhan proyek Anda...">{{ old('detail_project') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Mobile Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email ?? '') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('front.penawaran.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md">Kirim PO</button>
            </div>
        </form>

    </div>
</div>
@endsection
