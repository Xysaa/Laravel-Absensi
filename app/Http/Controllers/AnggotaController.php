<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Models\QrCode;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
    $request->validate([
        'file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('file');
    $path = $file->getRealPath();

    $csv = Reader::createFromPath($path, 'r');
    $csv->setHeaderOffset(0);
    $records = (new Statement())->process($csv);

    $errors = [];

    foreach ($records as $index => $record) {
        $validator = Validator::make($record, [
            'nim' => 'required|string|unique:anggotas,nim',
            'nama' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status_anggota' => 'required|string|in:Anggota Tetap,Anggota Muda',
        ]);

        if ($validator->fails()) {
            $errors[] = "Baris " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
            continue;
        }

        try {
            DB::transaction(function () use ($record) {
                $anggota = Anggota::create([
                    'nim' => $record['nim'],
                    'nama' => $record['nama'],
                    'angkatan' => $record['angkatan'],
                    'status_anggota' => $record['status_anggota'],
                ]);

                QrCode::create([
                    'anggota_id' => $anggota->id,
                    'qrcode' => $anggota->nim,
                ]);
            });
        } catch (\Exception $e) {
            Log::error("Import error on row " . ($index + 2) . ": " . $e->getMessage());
            $errors[] = "Baris " . ($index + 2) . ": Gagal menyimpan data.";
        }
    }

    if (count($errors) > 0) {
        return redirect()->route('anggota.index')->with([
            'import_errors' => $errors,
            'success' => 'Import selesai dengan beberapa kesalahan.',
        ]);
    }

    return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diimport.');
}
    public function massUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status_anggota' => 'required|string',
        ]);

        Anggota::whereIn('id', $request->ids)->update([
            'status_anggota' => $request->status_anggota
        ]);

        return redirect()->route('anggota.index')->with('success', 'Status anggota berhasil diperbarui.');
    }


}