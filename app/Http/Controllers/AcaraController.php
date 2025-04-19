<?php

namespace App\Http\Controllers;
use App\Models\Acara;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        $kehadiran = $acara->kehadirans()->with('anggota')->get();
        return view('acara.show', compact('acara', 'kehadiran'));
    }


    public function edit(Acara $acara)
    {
        return view('acara.edit', compact('acara'));
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
        ]);

        $validated['is_active'] = $request->has('is_active');

        $acara->update($validated);

        return redirect()->route('acara.index')
            ->with('success', 'Acara updated successfully.');
    }

    public function destroy(Acara $acara)
    {
        $acara->delete();

        return redirect()->route('acara.index')
            ->with('success', 'acara deleted successfully.');
    }
}
