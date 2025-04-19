<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $students = Anggota::orderBy('nim')->paginate(15);
        return view('admin.anggota.index', compact('students'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:anggota,nim',
            'nama' => 'required|string|max:255',
            'status_anggota' => 'required|string|max:100',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        Student::create($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        return view('admin.anggota.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:anggota,nim,' . $student->id,
            'nama' => 'required|string|max:255',
            'status_anggota' => 'required|string|max:100',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $student->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}