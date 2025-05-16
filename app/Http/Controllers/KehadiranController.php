<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Kehadiran;
use App\Models\Anggota;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        if ($request->has('event_id')) {
            $activeEvents = Acara::where('id', $request->event_id)
                ->where('is_active', true)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now)
                ->get();
        } else {
            $activeEvents = Acara::where('is_active', true)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now)
                ->orderBy('start_time')
                ->get();
        }

        return view('kehadiran.index', compact('activeEvents'));
    }

    public function record(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:acaras,id',
            'nim'      => 'required|string',
        ]);

        $event = Acara::findOrFail($validated['event_id']);
        $nim   = $validated['nim'];

        $student = Anggota::where('nim', $nim)->first();

        if (!$student) {
            return back()->with('error', 'Mahasiswa dengan NIM ini tidak ditemukan.');
        }

        if (!$event->isInProgress()) {
            return back()->with('error', 'Acara tidak sedang berlangsung.');
        }

        if ($student->hasAttendedEvent($event->id)) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk acara ini.');
        }

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