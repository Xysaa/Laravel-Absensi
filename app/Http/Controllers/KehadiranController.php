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
        // Validasi input
        $validated = $request->validate([
            'event_id' => 'required|exists:acaras,id',
            'nim'      => 'required|string',  // NIM plain text
        ]);

        $event = Acara::findOrFail($validated['event_id']);
        $nim   = $validated['nim'];

        // Cari mahasiswa berdasarkan NIM
        $student = Anggota::where('nim', $nim)->first();

        if (!$student) {
            return back()->with('error', 'Mahasiswa dengan NIM ini tidak ditemukan.');
        }

        // Cek apakah event sedang berlangsung
        if (!$event->isInProgress()) {
            return back()->with('error', 'Acara tidak sedang berlangsung.');
        }

        // Cek apakah sudah absen
        if ($student->hasAttendedEvent($event->id)) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk acara ini.');
        }

        // Catat kehadiran
        Kehadiran::create([
            'event_id'      => $event->id,
            'student_id'    => $student->id,
            'check_in_time' => now(),
            'status'        => 'hadir',
        ]);

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
