<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Models\QrCode;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function index(Request $request)
    {
        $query = Anggota::query();

        // Cek jika ada input pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        // Ambil data mahasiswa dengan pagination
        $students = $query->paginate(10);

        return view('anggota.index', compact('students'));
    }


    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'            => 'required|string|unique:anggotas,nim',
            'nama'           => 'required|string|max:255',
            'status_anggota' => 'required|string|max:100',
            'angkatan'       => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        // Simpan anggota baru, dan tangkap instance-nya
        $anggota = Anggota::create([
            'nim'            => $validated['nim'],
            'nama'           => $validated['nama'],
            'status_anggota' => $validated['status_anggota'],
            'angkatan'       => $validated['angkatan'],
        ]);

        // Buat record QR code di tabel qr_codes
        QrCode::create([
            'anggota_id' => $anggota->id,
            'qrcode'     => $anggota->nim,
        ]);

        // Redirect dengan notifikasi sukses
        return redirect()
            ->route('anggota.index')
            ->with('success', 'Data mahasiswa & QR code berhasil dibuat.');
    }

    public function edit(Anggota $anggota)
    {
        if (!$anggota) {
            return redirect()->route('anggota.index')->with('error', 'Data anggota tidak ditemukan.');
        }
        return view('anggota.edit', compact('anggota'));
    }


    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:anggotas,nim,' . $anggota->id,
            'nama' => 'required|string|max:255',
            'status_anggota' => 'required|string|max:100',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        // Hapus record QR code terkait
        QrCode::where('anggota_id', $anggota->id)->delete();

        // Hapus anggota
        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    // Tambah metode untuk import CSV
    public function importForm()
    {
        return view('anggota.import');
    }
    public function show(Anggota $anggota)
    {
        // Optional: Retrieve related data if needed
        $qrCode = QrCode::where('anggota_id', $anggota->id)->first();
        
        return view('anggota.show', compact('anggota', 'qrCode'));
    }

    public function importProcess(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Baca file CSV menggunakan Laravel's File facade
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        // Baca konten file langsung sebagai string untuk diproses dengan Papaparse
        $csvContent = file_get_contents($path);
        
        // Coba deteksi dan perbaiki masalah encoding
        if (!mb_detect_encoding($csvContent, 'UTF-8', true)) {
            $csvContent = mb_convert_encoding($csvContent, 'UTF-8');
        }
        
        // Gunakan fungsi str_getcsv untuk parsing lebih fleksibel
        $rows = array_map('str_getcsv', explode("\n", $csvContent));
        
        // Pemrosesan header - hapus karakter BOM jika ada dan whitespace
        $header = $rows[0];
        $header = array_map(function($field) {
            return trim(str_replace("\xEF\xBB\xBF", '', $field)); // Menghapus BOM dan whitespace
        }, $header);
        
        // Tentukan header yang diharapkan
        $expectedHeaders = ['nim', 'nama', 'status_anggota', 'angkatan'];
        
        // Periksa format header dengan lebih fleksibel (case insensitive)
        $headerMatches = true;
        foreach ($expectedHeaders as $expected) {
            $found = false;
            foreach ($header as $actual) {
                if (strtolower($actual) === strtolower($expected)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $headerMatches = false;
                break;
            }
        }
        
        if (!$headerMatches) {
            return redirect()->back()->with('error', 'Format CSV tidak valid. Header yang diperlukan: nim, nama, status_anggota, angkatan. Header yang ditemukan: ' . implode(', ', $header));
        }
        
        // Normalisasi header agar case-nya sesuai dengan yang diharapkan sistem
        $normalizedHeader = [];
        foreach ($header as $key => $value) {
            foreach ($expectedHeaders as $expected) {
                if (strtolower($value) === strtolower($expected)) {
                    $normalizedHeader[$key] = $expected;
                    break;
                }
            }
        }
        
        // Inisialisasi counter
        $totalImported = 0;
        $totalSkipped = 0;
        $errors = [];

        // Proses setiap baris (mulai dari indeks 1 karena 0 adalah header)
        for ($i = 1; $i < count($rows); $i++) {
            // Lewati baris kosong
            if (empty($rows[$i]) || count(array_filter($rows[$i])) == 0) {
                continue;
            }
            
            // Pastikan jumlah kolom sesuai
            if (count($rows[$i]) != count($normalizedHeader)) {
                $totalSkipped++;
                $errors[] = "Baris #$i: Jumlah kolom tidak sesuai dengan header";
                continue;
            }
            
            // Map data ke array asosiatif dengan header yang sudah dinormalisasi
            $rowData = [];
            foreach ($rows[$i] as $index => $value) {
                if (isset($normalizedHeader[$index])) {
                    $rowData[$normalizedHeader[$index]] = trim($value);
                }
            }
            
            // Pastikan semua kolom yang diharapkan ada
            if (count(array_intersect_key(array_flip($expectedHeaders), $rowData)) != count($expectedHeaders)) {
                $totalSkipped++;
                $errors[] = "Baris #$i: Data tidak lengkap";
                continue;
            }
            
            // Validasi data
            $validator = Validator::make($rowData, [
                'nim'            => 'required|string|unique:anggotas,nim',
                'nama'           => 'required|string|max:255',
                'status_anggota' => 'required|string|max:100',
                'angkatan'       => 'required|integer|min:2000|max:' . (date('Y') + 1),
            ]);
            
            // Jika validasi gagal, catat error dan lanjutkan
            if ($validator->fails()) {
                $totalSkipped++;
                $nim = $rowData['nim'] ?? 'unknown';
                $errors[] = "Baris #$i (NIM: $nim): " . implode(', ', $validator->errors()->all());
                continue;
            }
            
            try {
                // Buat data anggota
                $anggota = Anggota::create([
                    'nim'            => $rowData['nim'],
                    'nama'           => $rowData['nama'],
                    'status_anggota' => $rowData['status_anggota'],
                    'angkatan'       => (int) $rowData['angkatan'],
                ]);
                
                // Buat QR code
                QrCode::create([
                    'anggota_id' => $anggota->id,
                    'qrcode'     => $anggota->nim,
                ]);
                
                $totalImported++;
            } catch (\Exception $e) {
                $totalSkipped++;
                $nim = $rowData['nim'] ?? 'unknown';
                $errors[] = "Error pada NIM $nim: " . $e->getMessage();
            }
        }
        
        // Siapkan pesan hasil
        $message = "Import selesai. $totalImported data berhasil diimport.";
        if ($totalSkipped > 0) {
            $message .= " $totalSkipped data dilewati.";
            session(['import_errors' => $errors]);
        }
        
        // Debug - tambahkan informasi header jika tidak ada data yang berhasil diimpor
        if ($totalImported == 0) {
            session(['debug_info' => [
                'detected_header' => $header,
                'normalized_header' => $normalizedHeader,
                'expected_header' => $expectedHeaders,
                'rows_count' => count($rows),
                'sample_row' => isset($rows[1]) ? $rows[1] : []
            ]]);
        }
        
        return redirect()->route('anggota.index')->with('success', $message);
    }
}