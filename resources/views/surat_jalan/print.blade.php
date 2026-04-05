<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Jalan - {{ $suratJalan->no_surat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            @page {
                size: A4;
                margin: 2cm;
            }
            body {
                background-color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page-break {
                page-break-before: always;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
            .no-print {
                display: none !important;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: black;
            line-height: 1.5;
        }
        .sans {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body class="bg-white text-black">
    <div class="max-w-[21cm] mx-auto pt-4">

        {{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6">
            <div class="w-full">
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat" class="w-full h-auto">
            </div>
        </header>

        {{-- JUDUL DOKUMEN --}}
        <div class="text-center mb-8">
            <h2 class="text-lg font-bold underline uppercase">SURAT JALAN / PENGIRIMAN BARANG</h2>
            <p class="font-bold text-md mt-1">No. {{ $suratJalan->no_surat }}</p>
        </div>

        @php
        use Carbon\Carbon;
        $date = Carbon::parse($suratJalan->tanggal);
        $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $hari = $days[$date->format('l')];
        $tanggal = $date->format('d');
        $bulan = $months[(int)$date->format('m')];
        $tahun = $date->format('Y');
        @endphp

        {{-- ISI SURAT --}}
        <div class="text-justify text-md space-y-4">
            <p>
                Telah dikirimkan barang dengan rincian sebagai berikut pada hari <strong>{{ $hari }}</strong>, <strong>{{ $tanggal }} {{ $bulan }} {{ $tahun }}</strong>:
            </p>

            <div class="grid grid-cols-2 gap-8 mb-4">
                <!-- PENGIRIM -->
                <div>
                    <h3 class="font-bold underline mb-2">PENGIRIM</h3>
                    <table class="w-full">
                        <tr>
                            <td class="align-top w-24">Nama</td>
                            <td class="align-top w-4">:</td>
                            <td class="align-top font-bold">{{ $suratJalan->sumber_pengirim }}</td>
                        </tr>
                    </table>
                </div>

                <!-- PENERIMA -->
                <div>
                    <h3 class="font-bold underline mb-2">PENERIMA / TUJUAN</h3>
                    <table class="w-full">
                        <tr>
                            <td class="align-top w-24">Nama</td>
                            <td class="align-top w-4">:</td>
                            <td class="align-top font-bold">{{ $suratJalan->penerima_nama }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Instansi</td>
                            <td class="align-top">:</td>
                            <td class="align-top">{{ $suratJalan->penerima_instansi }}</td>
                        </tr>
                        <tr>
                            <td class="align-top">Alamat</td>
                            <td class="align-top">:</td>
                            <td class="align-top">{{ $suratJalan->penerima_alamat }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- RINCIAN PRODUK -->
            <div class="mt-6">
                <table class="w-full border-collapse border border-gray-800 text-sm sans">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-800 px-3 py-2 text-center w-12">No</th>
                            <th class="border border-gray-800 px-3 py-2">Nama Barang / Deskripsi</th>
                            <th class="border border-gray-800 px-3 py-2 text-center w-24">QTY</th>
                            <th class="border border-gray-800 px-3 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suratJalan->offer->items as $index => $item)
                        <tr>
                            <td class="border border-gray-800 px-3 py-1 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-800 px-3 py-1">{{ $item->nama_produk }}</td>
                            <td class="border border-gray-800 px-3 py-1 text-center">{{ $item->volume }} {{ $item->satuan }}</td>
                            <td class="border border-gray-800 px-3 py-1">{{ $item->deskripsi_tambahan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($suratJalan->catatan_pengiriman)
                <p class="mt-4 break-words"><strong>Catatan Pengiriman:</strong><br/>
                {{ $suratJalan->catatan_pengiriman }}</p>
                @endif
            </div>

            <p class="mt-6">Demikian Surat Jalan ini dibuat untuk dipergunakan sebagaimana mestinya. Harap barang dicek kondisinya dengan baik saat diterima.</p>
        </div>

        {{-- TANDA TANGAN --}}
        <div class="mt-16 flex justify-between text-center px-8 avoid-break">
            <div class="w-5/12">
                <p class="mb-4">Penerima Barang</p>
                <div class="h-24"></div> 
                <p class="font-bold underline uppercase">{{ $suratJalan->penerima_nama }}</p>
                <p>{{ $suratJalan->penerima_instansi }}</p>
            </div>
            <div class="w-5/12">
                <p class="mb-4">Pengirim Barang</p>
                <div class="h-24 flex justify-center items-center">
                    <img src="{{ asset('images/ttd.png') }}" class="h-24 object-contain opacity-100">
                </div>
                <p class="font-bold underline uppercase mt-2">{{ $suratJalan->sumber_pengirim }}</p>
            </div>
        </div>

        {{-- LAMPIRAN FOTO (HALAMAN BARU) --}}
        <div class="page-break"></div>

        <div class="mt-8 sans">
            <h2 class="text-xl font-bold text-center underline uppercase mb-8">LAMPIRAN DOKUMENTASI BARANG</h2>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-md font-bold text-center bg-gray-200 py-2 border border-gray-400">SAAT DIKIRIM (SEBELUM)</h3>
                    @if(isset($beforeImages) && count($beforeImages) > 0)
                    @foreach($beforeImages as $img)
                    <div class="border border-gray-400 p-2 avoid-break">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-48 object-cover block" alt="Foto Sebelum">
                    </div>
                    @endforeach
                    @else
                    <p class="text-center italic text-gray-500 py-10 border border-dashed border-gray-300">Tidak ada dokumentasi.</p>
                    @endif
                </div>

                <div class="space-y-6">
                    <h3 class="text-md font-bold text-center bg-gray-200 py-2 border border-gray-400">DITERIMA (SESUDAH)</h3>
                    @if(isset($afterImages) && count($afterImages) > 0)
                    @foreach($afterImages as $img)
                    <div class="border border-gray-400 p-2 avoid-break">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-48 object-cover block" alt="Foto Sesudah">
                    </div>
                    @endforeach
                    @else
                    <p class="text-center italic text-gray-500 py-10 border border-dashed border-gray-300">Tidak ada dokumentasi.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 800);
        };
    </script>
</body>
</html>
