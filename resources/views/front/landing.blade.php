<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick.Deal - Solusi Administrasi Bisnis Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .dark-glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); }
        .gradient-text { @apply text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600; }
        .btn-gradient { @apply bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-300 transform hover:-translate-y-0.5; }
        .card-glow:hover { box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.15); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-blue-500 selection:text-white overflow-x-hidden">

    <!-- Sticky Navbar -->
    <nav class="fixed w-full z-40 glass-effect border-b border-slate-200/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-app.png') }}" alt="Logo" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="text-xl font-extrabold tracking-tight text-slate-900">Quick<span class="text-blue-600">.Deal</span></span>
            </a>
            <div class="hidden md:flex items-center gap-8 font-semibold text-slate-650">
                <a href="#fitur" class="hover:text-blue-600 transition">Fitur</a>
                <a href="#statistik" class="hover:text-blue-600 transition">Kelebihan</a>
                <a href="#kontak" class="hover:text-blue-600 transition">Kontak</a>
                <a href="{{ route('front.penawaran.index') }}" class="text-blue-600 hover:text-blue-700 font-bold transition">Penawaran Publik</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-slate-700 hover:text-blue-600 font-bold text-sm px-4 py-2 transition">Masuk</a>
                <a href="https://wa.me/6281393044942?text=Halo%20Admin%20Quick.Deal,%20saya%20ingin%20bertanya%20mengenai%20layanan%20aplikasi..." 
                   target="_blank" 
                   class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                    <!-- WA Icon -->
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.858.002-2.634-1.018-5.11-2.871-6.968C16.592 1.96 14.12 1.01 11.998 1.01c-5.442 0-9.866 4.42-9.87 9.859-.001 1.765.483 3.486 1.4 5.018L2.5 21.5l5.59-1.465.557.347zm11.758-5.326c-.322-.161-1.905-.94-2.202-1.05-.297-.109-.514-.162-.73.162-.217.322-.84.105-1.03.322-.19.215-.378.107-.7.054-.323-.054-1.362-.502-2.595-1.601-.959-.856-1.607-1.912-1.796-2.234-.19-.323-.02-.497.14-.657.146-.145.32-.377.483-.566.161-.188.215-.322.322-.538.109-.217.055-.404-.027-.565-.082-.162-.73-1.758-1.002-2.414-.265-.637-.534-.55-.73-.56-.19-.008-.407-.01-.624-.01-.217 0-.569.082-.867.408-.297.322-1.137 1.112-1.137 2.71 0 1.597 1.163 3.14 1.325 3.357.162.217 2.288 3.496 5.542 4.9.774.333 1.378.533 1.849.683.778.247 1.488.212 2.049.128.625-.093 1.905-.778 2.175-1.492.27-.714.27-1.325.19-1.458-.08-.135-.297-.216-.62-.378z"/>
                    </svg>
                    Hubungi Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-28 px-6 overflow-hidden bg-gradient-to-b from-blue-50/50 via-slate-50 to-slate-50">
        <!-- Background Glowing Circles -->
        <div class="absolute top-1/4 right-0 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 left-10 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase border border-blue-100 shadow-sm animate-pulse">
                🚀 Revolusi Administrasi Proyek & Penawaran
            </span>
            <h1 class="mt-8 text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Buat Penawaran & Invoice <br> 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600">Dalam Hitungan Detik.</span>
            </h1>
            <p class="mt-6 text-lg md:text-xl text-slate-650 max-w-3xl mx-auto leading-relaxed">
                Kelola katalog harga, otomatisasi surat penawaran, pantau negosiasi harga, dan kelola invoice & BAST dalam satu platform terintegrasi. Profesional, cepat, dan transparan.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('front.penawaran.index') }}" class="btn-gradient px-8 py-4 rounded-2xl font-bold text-lg inline-flex items-center gap-2 w-full sm:w-auto justify-center">
                    🔍 Lihat Penawaran Publik
                </a>
                <a href="#fitur" class="bg-white border border-slate-250 text-slate-700 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition shadow-sm hover:shadow-md w-full sm:w-auto justify-center inline-flex">
                    Pelajari Fitur &rarr;
                </a>
            </div>

            <!-- Beautiful Custom HTML/CSS Mockup of Dashboard -->
            <div class="mt-20 max-w-5xl mx-auto relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-[32px] blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>
                <div class="relative rounded-3xl bg-slate-950 text-slate-200 border border-slate-800 shadow-2xl overflow-hidden p-1.5">
                    <!-- Window Header -->
                    <div class="flex items-center justify-between px-6 py-4 bg-slate-900/60 rounded-t-2xl border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-rose-500 inline-block"></span>
                            <span class="w-3.5 h-3.5 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 inline-block"></span>
                        </div>
                        <div class="bg-slate-950 px-8 py-1.5 rounded-lg border border-slate-800/80 text-[11px] text-slate-400 font-mono tracking-wider">
                            app.quickdeal.biz.id/dashboard
                        </div>
                        <div class="w-12"></div>
                    </div>
                    
                    <!-- App Interface Layout -->
                    <div class="grid grid-cols-12 text-left bg-slate-950 font-sans min-h-[420px]">
                        <!-- Sidebar Mockup -->
                        <div class="col-span-3 border-r border-slate-800/80 p-4 space-y-6 hidden md:block bg-slate-900/10">
                            <div class="flex items-center gap-2.5 px-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-xs text-white">Q</div>
                                <span class="font-bold text-sm tracking-tight">Quick.Deal</span>
                            </div>
                            <div class="space-y-1">
                                <div class="bg-blue-600/15 text-blue-400 flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold">
                                    <span>📊</span> Dashboard
                                </div>
                                <div class="text-slate-400 hover:text-slate-200 flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold cursor-pointer">
                                    <span>🛍️</span> Katalog Harga
                                </div>
                                <div class="text-slate-400 hover:text-slate-200 flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold cursor-pointer">
                                    <span>✉️</span> Buat Penawaran
                                </div>
                                <div class="text-slate-400 hover:text-slate-200 flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold cursor-pointer">
                                    <span>📂</span> Histori Data
                                </div>
                                <div class="text-slate-400 hover:text-slate-200 flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold cursor-pointer">
                                    <span>⚙️</span> Pengaturan
                                </div>
                            </div>
                        </div>

                        <!-- Content Area Mockup -->
                        <div class="col-span-12 md:col-span-9 p-6 space-y-6 bg-slate-950">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-base font-extrabold text-white">Halo, CV. Sukses Makmur 👋</h3>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Pantau dan kelola surat penawaran aktif Anda</p>
                                </div>
                                <div class="flex gap-2">
                                    <span class="bg-emerald-500/15 text-emerald-400 border border-emerald-500/35 px-2.5 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Portal Klien Aktif
                                    </span>
                                </div>
                            </div>

                            <!-- Small Stats Row -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Estimasi Proyek</span>
                                    <h4 class="text-base font-extrabold text-white mt-1">Rp 120.000.000</h4>
                                    <span class="text-[9px] text-emerald-400 font-bold block mt-1">+15% dari target</span>
                                </div>
                                <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Tagihan Invoice</span>
                                    <h4 class="text-base font-extrabold text-white mt-1">8 Invoice</h4>
                                    <span class="text-[9px] text-amber-400 font-bold block mt-1">2 Menunggu transfer</span>
                                </div>
                                <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Negosiasi</span>
                                    <h4 class="text-base font-extrabold text-white mt-1">3 Pengajuan</h4>
                                    <span class="text-[9px] text-blue-400 font-bold block mt-1">Status: Diproses</span>
                                </div>
                            </div>

                            <!-- Recent Documents Table Mockup -->
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 bg-slate-900 border-b border-slate-800 flex justify-between items-center">
                                    <span class="text-xs font-bold text-white">Aktivitas Penawaran Terakhir</span>
                                    <span class="text-[10px] text-blue-400 cursor-pointer font-bold hover:underline">Lihat Semua &rarr;</span>
                                </div>
                                <div class="divide-y divide-slate-800 text-xs">
                                    <div class="p-3.5 flex justify-between items-center">
                                        <div class="space-y-0.5">
                                            <div class="font-bold text-white text-xs">Pekerjaan Sipil Pengecatan Gedung</div>
                                            <div class="text-[10px] text-slate-500">Kategori: Proyek Jasa | Tanggal: 18 Jun 2026</div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="font-extrabold text-slate-100 text-xs">Rp 45.000.000</span>
                                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 rounded-full text-[10px] font-bold">Approved</span>
                                        </div>
                                    </div>
                                    <div class="p-3.5 flex justify-between items-center">
                                        <div class="space-y-0.5">
                                            <div class="font-bold text-white text-xs">Suplai Cat Jotun Exterior Premium</div>
                                            <div class="text-[10px] text-slate-500">Kategori: Produk Supply | Tanggal: 17 Jun 2026</div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="font-extrabold text-slate-100 text-xs">Rp 12.500.000</span>
                                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2.5 py-0.5 rounded-full text-[10px] font-bold">Negosiasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="statistik" class="py-20 bg-slate-900 text-white relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="space-y-2">
                    <div class="text-4xl md:text-5xl font-extrabold text-blue-500">5.000+</div>
                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider">Dokumen Dibuat</p>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl md:text-5xl font-extrabold text-indigo-500">250+</div>
                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider">Perusahaan Klien</p>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl md:text-5xl font-extrabold text-emerald-500">99.9%</div>
                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider">Akurasi Perhitungan</p>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl md:text-5xl font-extrabold text-pink-500">24/7</div>
                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider">Dukungan Respons Cepat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-28 bg-white px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <span class="text-blue-600 font-bold uppercase tracking-wider text-sm">Alur Kerja Cerdas</span>
                <h2 class="text-4xl font-extrabold text-slate-900 mt-3">Segala yang Anda Butuhkan untuk Administrasi Cepat</h2>
                <p class="text-slate-500 mt-4 leading-relaxed">Kelola administrasi bisnis Anda secara otomatis dari hulu ke hilir tanpa tulis ulang data.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-150 hover:border-blue-300 transition duration-300 group card-glow flex flex-col justify-between">
                    <div>
                        <div class="h-14 w-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Penawaran Cepat</h3>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Pilih daftar produk atau jasa, tentukan volume, diskon per item, dan unduh PDF penawaran profesional berlogo resmi instansi Anda.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-150 hover:border-indigo-300 transition duration-300 group card-glow flex flex-col justify-between">
                    <div>
                        <div class="h-14 w-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Histori & Rekap Terpusat</h3>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Cari data penawaran lama, buat rekapitulasi pengeluaran ke format Excel/Word dengan sekali klik untuk kebutuhan pertanggungjawaban internal.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-150 hover:border-emerald-300 transition duration-300 group card-glow flex flex-col justify-between">
                    <div>
                        <div class="h-14 w-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Siap Cetak SPK & BAST</h3>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Otomatisasi cetak dokumen berita acara serah terima pekerjaan (BAST), invoice termin, dan surat jalan pengiriman produk secara instan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp CTA Section -->
    <section id="kontak" class="py-24 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white relative px-6">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.1),transparent_50%)]"></div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-500/10 text-[#25D366] border border-emerald-500/20 rounded-3xl mb-8">
                <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.858.002-2.634-1.018-5.11-2.871-6.968C16.592 1.96 14.12 1.01 11.998 1.01c-5.442 0-9.866 4.42-9.87 9.859-.001 1.765.483 3.486 1.4 5.018L2.5 21.5l5.59-1.465.557.347zm11.758-5.326c-.322-.161-1.905-.94-2.202-1.05-.297-.109-.514-.162-.73.162-.217.322-.84.105-1.03.322-.19.215-.378.107-.7.054-.323-.054-1.362-.502-2.595-1.601-.959-.856-1.607-1.912-1.796-2.234-.19-.323-.02-.497.14-.657.146-.145.32-.377.483-.566.161-.188.215-.322.322-.538.109-.217.055-.404-.027-.565-.082-.162-.73-1.758-1.002-2.414-.265-.637-.534-.55-.73-.56-.19-.008-.407-.01-.624-.01-.217 0-.569.082-.867.408-.297.322-1.137 1.112-1.137 2.71 0 1.597 1.163 3.14 1.325 3.357.162.217 2.288 3.496 5.542 4.9.774.333 1.378.533 1.849.683.778.247 1.488.212 2.049.128.625-.093 1.905-.778 2.175-1.492.27-.714.27-1.325.19-1.458-.08-.135-.297-.216-.62-.378z"/>
                </svg>
            </div>
            <h2 class="text-4xl font-extrabold mb-4">Butuh Penyesuaian Administrasi Khusus?</h2>
            <p class="text-slate-400 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                Kami siap membantu menjawab kebutuhan tender, perubahan penawaran harga, pembuatan invoice kustom, atau bantuan teknis operasional aplikasi secara langsung.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="https://wa.me/6281393044942?text=Halo%20Admin%20Quick.Deal,%20saya%20tertarik%20menggunakan%20aplikasi%20ini%20dan%20ingin%20berkonsultasi..." 
                   target="_blank"
                   class="bg-[#25D366] hover:bg-[#128C7E] text-white font-extrabold text-lg px-8 py-4 rounded-2xl transition duration-300 inline-flex items-center gap-3 shadow-lg shadow-emerald-500/20">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.858.002-2.634-1.018-5.11-2.871-6.968C16.592 1.96 14.12 1.01 11.998 1.01c-5.442 0-9.866 4.42-9.87 9.859-.001 1.765.483 3.486 1.4 5.018L2.5 21.5l5.59-1.465.557.347zm11.758-5.326c-.322-.161-1.905-.94-2.202-1.05-.297-.109-.514-.162-.73.162-.217.322-.84.105-1.03.322-.19.215-.378.107-.7.054-.323-.054-1.362-.502-2.595-1.601-.959-.856-1.607-1.912-1.796-2.234-.19-.323-.02-.497.14-.657.146-.145.32-.377.483-.566.161-.188.215-.322.322-.538.109-.217.055-.404-.027-.565-.082-.162-.73-1.758-1.002-2.414-.265-.637-.534-.55-.73-.56-.19-.008-.407-.01-.624-.01-.217 0-.569.082-.867.408-.297.322-1.137 1.112-1.137 2.71 0 1.597 1.163 3.14 1.325 3.357.162.217 2.288 3.496 5.542 4.9.774.333 1.378.533 1.849.683.778.247 1.488.212 2.049.128.625-.093 1.905-.778 2.175-1.492.27-.714.27-1.325.19-1.458-.08-.135-.297-.216-.62-.378z"/>
                    </svg>
                    Hubungi WhatsApp Admin (0813-9304-4942)
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-450 border-t border-slate-850 py-16 px-6 relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="space-y-4">
                <div class="flex items-center gap-3 text-white">
                    <img src="{{ asset('images/logo-app.png') }}" alt="Logo" class="h-8 w-auto object-contain brightness-0 invert">
                    <span class="text-lg font-bold">Quick.Deal</span>
                </div>
                <p class="text-sm text-slate-400">
                    Aplikasi cerdas administrasi penawaran harga produk & jasa untuk mempercepat bisnis dan operasional industri terpercaya.
                </p>
            </div>
            
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm uppercase tracking-wider">Kontak & Admin</h4>
                <p class="text-sm text-slate-400">
                    CV. DAEDAN ENTERPRISE / PT. Tasniem Gerai Inspirasi<br>
                    <strong>WhatsApp:</strong> <a href="https://wa.me/6281393044942" class="text-emerald-450 hover:underline">0813-9304-4942</a><br>
                    <strong>Website:</strong> <a href="https://suratpenawaran.biz.id" target="_blank" class="text-blue-400 hover:underline">suratpenawaran.biz.id</a>
                </p>
            </div>
            
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm uppercase tracking-wider">Menu Cepat</h4>
                <div class="flex flex-col gap-2.5 text-sm">
                    <a href="{{ route('front.penawaran.index') }}" class="text-slate-400 hover:text-white transition">Katalog Penawaran Publik</a>
                    <a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition">Login Pengguna</a>
                    <a href="{{ route('register') }}" class="text-slate-400 hover:text-white transition">Daftar Akun Klien</a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto border-t border-slate-800/80 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <p>&copy; 2026 Quick.Deal (CV. Daedan Enterprise). Hak Cipta Dilindungi.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-slate-300 transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-slate-300 transition">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    <!-- Include Floating WhatsApp Widget -->
    @include('partials.whatsapp-btn')

</body>
</html>