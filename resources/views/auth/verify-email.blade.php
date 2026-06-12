<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Quick.Deal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-gray-100">

        <div class="text-center mb-6">
            <!-- Icon Amplop Email -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4.8a2 2 0 012.22 0l8 4.8A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l8.25 5.5a2 2 0 002.22 0L21 10" />
                </svg>
            </div>
            
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                Verifikasi Email Anda
            </h1>
            <p class="text-gray-500 text-sm mt-2">Pendaftaran berhasil! Tinggal satu langkah lagi.</p>
        </div>

        <div class="text-gray-600 text-sm text-center mb-6 leading-relaxed">
            Terima kasih telah mendaftar di <strong>Quick.Deal</strong>! Sebelum dapat mengakses sistem manajemen terintegrasi kami, silakan verifikasi alamat email Anda dengan mengeklik tautan yang telah kami kirimkan.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg text-sm text-left animate-fade-in" role="alert">
                <p class="font-bold mb-1">Email Terkirim!</p>
                <p>Link verifikasi baru telah dikirimkan ke alamat email Anda. Silakan periksa inbox atau folder spam Anda.</p>
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form action="{{ route('verification.send') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 active:bg-blue-800 transform hover:-translate-y-0.5 transition duration-200 shadow-md">
                    Kirim Ulang Link Verifikasi
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-white border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 active:bg-gray-100 transition duration-200">
                    Keluar / Logout
                </button>
            </form>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} PT. Tasniem Gerai Inspirasi<br>
            Sistem Manajemen Terintegrasi
        </div>
    </div>

</body>
</html>
