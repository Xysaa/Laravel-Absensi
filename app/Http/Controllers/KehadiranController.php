<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Kehadiran;
use App\Models\Anggota;

class KehadiranController extends Controller
{
    public function index()
    {
        $now = now();
        $activeEvent = Acara::where('is_active', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->orderBy('start_time')
            ->first();

        return view('kehadiran.index', compact('activeEvent'));
    }

    public function record(Request $request)
    {
        \Log::info('Form data: ' . json_encode($request->all()));
        $validated = $request->validate([
            'nim' => 'required|string|exists:anggotas,nim',
            'event_id' => 'required|exists:acaras,id',
        ]);
        
        \Log::info('Validated data: ' . json_encode($validated));

        $event = Acara::findOrFail($validated['event_id']);
        $student = Anggota::where('nim', $validated['nim'])->firstOrFail();

        // Cek apakah event sedang berlangsung
        if (!$event->isInProgress()) {
            return back()->with('error', 'Acara tidak sedang berlangsung.');
        }

        // Cek apakah mahasiswa sudah absen
        \Log::info('Checking attendance for student ID: ' . $student->id . ', event ID: ' . $event->id);
        if ($student->hasAttendedEvent($event->id)) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk acara ini.');
        }

        // Catat kehadiran
        $kehadiran = Kehadiran::create([
            'event_id' => $event->id,
            'student_id' => $student->id,
            'check_in_time' => now(),
            'status' => 'hadir',
        ]);
        \Log::info('Kehadiran created: ' . json_encode($kehadiran));

        return back()->with('success', 'Absensi berhasil dicatat.');
    }

    public function adminIndex()
    {
        $events = Acara::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kehadiran.index', compact('events'));
    }

    public function eventAttendances(Acara $event)
    {
        $attendances = $event->attendances()->with('student')->get();
        return view('admin.kehadiran.show', compact('event', 'attendances'));
    }
}