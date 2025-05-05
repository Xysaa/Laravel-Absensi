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
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function index()
    {
        $acara = Acara::orderBy('created_at', 'desc')->paginate(10);
        return view('acara.index', compact('acara'));
    }

    public function create()
    {
        \Log::info('Accessing acara.create route');
        return view('acara.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'sometimes|boolean',
        ]);

        $acara = Acara::create([
            'judul_acara' => $validated['title'],
            'deskripsi' => $validated['description'],
            'lokasi' => $validated['location'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
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


    public function edit(Acara $acara)
    {
        return view('acara.edit', compact('acara'));
    }

    public function update(Request $request, Acara $acara)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'sometimes|boolean',
        ]);

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

        $filename = 'kehadiran_' . str_replace(' ', '_', strtolower($acara->title)) . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($acara, $kehadiran) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM untuk UTF-8 agar mendukung karakter khusus (misalnya, di Excel)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($file, ['No', 'Nama Acara','NIM', 'Nama Anggota']);

            // Data CSV
            foreach ($kehadiran as $index => $hadir) {
                fputcsv($file, [
                    $index + 1,
                    $acara->judul_acara,
                    $hadir->anggota->nim,
                    $hadir->anggota->nama,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
