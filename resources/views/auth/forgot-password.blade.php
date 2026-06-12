<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Quick.Deal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-gray-100">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                QUI<span class="text-blue-600">CK</span>.DEAL
            </h1>
            <p class="text-gray-500 text-sm mt-2">Lupa kata sandi? Masukkan email Anda untuk mendapatkan tautan pemulihan.</p>
        </div>

        @if (session('status'))
            <div class="mb-5 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg text-sm" role="alert">
                <p class="font-bold mb-1">Tautan Terkirim</p>
                <p>{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg text-sm" role="alert">
                <p class="font-bold mb-1">Permintaan Gagal</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                <input type="email" name="email" id="email"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                       placeholder="email@contoh.com"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 active:bg-blue-800 transform hover:-translate-y-0.5 transition duration-200 shadow-md mb-4">
                Kirim Link Pemulihan
            </button>

            <div class="text-center">
                <p class="text-sm text-gray-600">Kembali ke
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">Halaman Login</a>
                </p>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} PT. Tasniem Gerai Inspirasi<br>
            Sistem Manajemen Terintegrasi
        </div>
    </div>

</body>
</html>
