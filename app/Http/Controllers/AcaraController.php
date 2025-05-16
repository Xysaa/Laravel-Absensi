<?php

namespace App\Http\Controllers;
use App\Models\Acara;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Anggota;
use Illuminate\Support\Facades\Response;
class AcaraController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
        
    // }

    public function index()
    {
        $acara = Acara::orderBy('created_at', 'desc')->paginate(10);
        return view('acara.index', compact('acara'));
    }
    public function halamanacara(){
        $acara = Acara::orderBy('created_at', 'desc')->paginate(10);
        return view('halamanacara', compact('acara'));
    }
    public function halamandetailacara($id){
        $acara = Acara::findOrFail($id);
         
        return view('detailacara', compact('acara'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_acara' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'sometimes|boolean',
            'ketua_pelaksana' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename); // simpan ke public/img
            $fotoPath = 'img/' . $filename; // path yang akan disimpan di database
    }
 // simpan ke public/img
        }

        $acara = Acara::create([
            'judul_acara' => $validated['judul_acara'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
            'ketuplak' => $validated['ketua_pelaksana'],
            'foto' => $fotoPath,
        ]);
        

        return redirect()->route('acara.index')
            ->with('success', 'Event created successfully.');
    }


    public function show(Acara $acara)
    {
        // $kehadiran = $acara->kehadirans()->with('anggota')->get();
        // return view('acara.show', compact('acara', 'kehadiran'));
        $kehadiran = Kehadiran::where('event_id', $acara->id)->with('anggota')->get();
        $totalHadir = $kehadiran->count();
        $totalAnggota = Anggota::count();
        $totalTidakHadir = $totalAnggota - $totalHadir;

        return view('acara.detail', compact('acara', 'kehadiran', 'totalHadir', 'totalTidakHadir', 'totalAnggota'));
    }




    public function update(Request $request, Acara $acara)
    {
        $validated = $request->validate([
            'judul_acara' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'sometimes|boolean',
            'ketua_pelaksana' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $validated['foto'] = 'img/' . $filename;
        }

        $validated['is_active'] = $request->has('is_active');

        $acara->update($validated);

        return redirect()->route('acara.index')->with('success', 'Acara berhasil diperbarui.');
    }


    public function destroy(Acara $acara)
    {
        $acara->delete();

        return redirect()->route('acara.index')
            ->with('success', 'acara deleted successfully.');
    }
    public function exportCsv(Acara $acara)
    {
        $kehadiran = Kehadiran::where('event_id', $acara->id)->with('anggota')->get();

        // Membuat nama file dengan format 'kehadiran_nama_acara_tanggal.csv'
        $acaraTitle = str_replace(' ', '_', strtolower($acara->judul_acara)); // Ganti spasi dengan underscore
        $filename = 'kehadiran_' . $acaraTitle . '_' . now()->format('Y-m-d_H-i-s') . '.csv'; // Format tanggal dengan tahun-bulan-hari_jam-menit-detik

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($acara, $kehadiran) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM untuk UTF-8 agar mendukung karakter khusus (misalnya, di Excel)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($file, ['No', 'NIM', 'Nama Anggota']);

            // Data CSV
            foreach ($kehadiran as $index => $hadir) {
                fputcsv($file, [
                    $index + 1,
                    $hadir->anggota->nim,
                    $hadir->anggota->nama,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

}
