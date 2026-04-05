<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    // === Menampilkan Form Pembuatan Surat Jalan ===
    public function create(Offer $offer)
    {
        // Cek apakah Surat Jalan sudah ada (opsional jika ingin membatasi 1 surat jalan per offer)
        if ($offer->suratJalan) {
             return redirect()->back()->with('error', 'Surat Jalan untuk penawaran ini sudah dibuat.');
        }

        // Generate Nomor Surat Otomatis
        $bulanRomawi = ["", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII"];
        $nextId = SuratJalan::max('id') + 1;
        $noSurat = sprintf('%04d/SJ/TGI-1/%s/%s', $nextId, $bulanRomawi[date('n')], date('Y'));

        // Default Pengirim
        $defaultPengirim = 'PT TASNIEM GEMILANG INDONESIA';

        return view('surat_jalan.create', [
            'offer' => $offer,
            'noSurat' => $noSurat,
            'sumberPengirim' => $defaultPengirim
        ]);
    }

    // === Menyimpan Data Surat Jalan ===
    public function store(Request $request, Offer $offer)
    {
        // 1. Validasi Input
        $request->validate([
            'no_surat' => 'required|string',
            'sumber_pengirim' => 'nullable|string',
            'penerima_nama' => 'required|string',
            'penerima_instansi' => 'nullable|string',
            'penerima_alamat' => 'required|string',
            'catatan_pengiriman' => 'nullable|string',
            'before_images' => 'nullable|array',
            'before_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'after_images' => 'nullable|array',
            'after_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Proses Upload Gambar Before
        $beforePaths = [];
        if($request->hasFile('before_images')) {
            foreach($request->file('before_images') as $file) {
                // Simpan di folder public/sj-images
                $path = $file->store('sj-images', 'public');
                $beforePaths[] = $path;
            }
        }

        // 3. Proses Upload Gambar After
        $afterPaths = [];
        if($request->hasFile('after_images')) {
            foreach($request->file('after_images') as $file) {
                $path = $file->store('sj-images', 'public');
                $afterPaths[] = $path;
            }
        }

        // 4. Simpan ke Database
        $suratJalan = SuratJalan::create([
            'offer_id' => $offer->id,
            'no_surat' => $request->no_surat,
            'tanggal' => date('Y-m-d'), // Otomatis hari ini

            'sumber_pengirim' => $request->sumber_pengirim,
            
            'penerima_nama' => $request->penerima_nama,
            'penerima_instansi' => $request->penerima_instansi ?? '-',
            'penerima_alamat' => $request->penerima_alamat,
            
            'catatan_pengiriman' => $request->catatan_pengiriman,

            'before_image_path' => count($beforePaths) > 0 ? json_encode($beforePaths) : null,
            'after_image_path' => count($afterPaths) > 0 ? json_encode($afterPaths) : null,
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat Jalan berhasil disimpan!');
    }

    // === Method Print ===
    public function print($id)
    {
        $suratJalan = SuratJalan::findOrFail($id);

        // Proses Gambar Before
        $rawBefore = $suratJalan->before_image_path; 
        $beforeImages = [];
        if (!empty($rawBefore)) {
            $decoded = json_decode($rawBefore);
            $beforeImages = is_array($decoded) ? $decoded : (is_string($rawBefore) ? [$rawBefore] : []);
        }

        // Proses Gambar After
        $rawAfter = $suratJalan->after_image_path; 
        $afterImages = [];
        if (!empty($rawAfter)) {
            $decoded = json_decode($rawAfter);
            $afterImages = is_array($decoded) ? $decoded : (is_string($rawAfter) ? [$rawAfter] : []);
        }

        return view('surat_jalan.print', compact('suratJalan', 'beforeImages', 'afterImages'));
    }

    // === Menampilkan Daftar Histori Surat Jalan ===
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = SuratJalan::query()->with('offer');

        if ($search) {
            $query->where('no_surat', 'like', '%' . $search . '%')
                  ->orWhereHas('offer', function ($q) use ($search) {
                      $q->where('nama_klien', 'like', '%' . $search . '%');
                  });
        }

        $suratJalans = $query->latest()->paginate(15);

        return view('surat_jalan.index', compact('suratJalans', 'search'));
    }

    // === Menampilkan Detail Surat Jalan ===
    public function show(SuratJalan $suratJalan)
    {
        $suratJalan->load('offer');

        $beforeImages = [];
        if ($suratJalan->before_image_path) {
            $decoded = json_decode($suratJalan->before_image_path);
            $beforeImages = is_array($decoded) ? $decoded : [$suratJalan->before_image_path];
        }

        $afterImages = [];
        if ($suratJalan->after_image_path) {
            $decoded = json_decode($suratJalan->after_image_path);
            $afterImages = is_array($decoded) ? $decoded : [$suratJalan->after_image_path];
        }

        return view('surat_jalan.show', compact('suratJalan', 'beforeImages', 'afterImages'));
    }

    // === Menghapus Surat Jalan ===
    public function destroy(SuratJalan $suratJalan)
    {
        // 1. Hapus File Before
        if ($suratJalan->before_image_path) {
            $beforeImages = json_decode($suratJalan->before_image_path);
            if (is_array($beforeImages)) {
                foreach ($beforeImages as $path) {
                    if($path) Storage::disk('public')->delete($path);
                }
            }
        }

        // 2. Hapus File After
        if ($suratJalan->after_image_path) {
            $afterImages = json_decode($suratJalan->after_image_path);
            if (is_array($afterImages)) {
                foreach ($afterImages as $path) {
                    if($path) Storage::disk('public')->delete($path);
                }
            }
        }

        // 3. Hapus Record DB
        $suratJalan->delete();

        return redirect()->route('surat_jalan.index')->with('success', 'Surat Jalan berhasil dihapus!');
    }
}
