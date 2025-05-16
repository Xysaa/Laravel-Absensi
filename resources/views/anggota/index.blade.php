@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')
<header class="bg-white p-4 rounded shadow mb-6">
    <h1 class="text-xl font-semibold">Daftar Anggota</h1>
</header>

<section class="bg-white p-6 rounded shadow">
    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if (session('import_errors'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
        <p class="font-bold">Beberapa data gagal diimport:</p>
        <ul class="list-disc list-inside mt-2">
            @foreach (session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
        <div class="flex gap-2">
            <button onclick="openEventModal()" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                <iconify-icon icon="line-md:clipboard-plus" width="20" height="20"></iconify-icon>
                Tambah
            </button>

            <label class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                <iconify-icon icon="line-md:upload" width="20" height="20"></iconify-icon>
                <span>Import CSV</span>
                <form action="{{ route('anggota.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <input type="file" id="csvInput" name="file" accept=".csv" class="hidden" onchange="submitImportForm()">
                </form>
            </label>
        </div>

        <form action="{{ route('anggota.index') }}" method="GET" class="flex gap-2 w-full sm:w-1/2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari anggota..." 
                   class="px-3 py-2 border rounded w-full">
            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <iconify-icon icon="heroicons:magnifying-glass" width="20" height="20"></iconify-icon>
            </button>
            <a href="{{ route('anggota.index') }}" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                <iconify-icon icon="heroicons:x-mark" width="20" height="20"></iconify-icon>
            </a>
        </form>
    </div>

    <div class="w-full overflow-x-auto">
        <table id="userTable" class="min-w-full bg-white border rounded shadow text-sm">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="text-left px-4 py-2 border-b">NO</th>
                    <th class="text-left px-4 py-2 border-b">NIM</th>
                    <th class="text-left px-4 py-2 border-b">Nama</th>
                    <th class="text-left px-4 py-2 border-b">Angkatan</th>
                    <th class="text-left px-4 py-2 border-b">Status</th>
                    <th class="text-left px-4 py-2 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $index => $student)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 font-semibold">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $student->nim }}</td>
                    <td class="px-4 py-2">{{ $student->nama }}</td>
                    <td class="px-4 py-2">{{ $student->angkatan }}</td>
                    <td class="px-4 py-2">{{ $student->status_anggota }}</td>
                    <td class="px-4 py-2">
                        <div class="flex gap-2">
                            <button onclick="openEditModal('{{ $student->id }}', '{{ $student->nim }}', '{{ $student->nama }}', '{{ $student->angkatan }}', '{{ $student->status_anggota }}')" 
                                    class="flex items-center gap-1 bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">
                                <iconify-icon icon="line-md:clipboard-list" width="18" height="18"></iconify-icon>
                                Edit
                            </button>
                            <form action="{{ route('anggota.destroy', $student) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')"
                                        class="flex items-center gap-1 bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    <iconify-icon icon="line-md:trash" width="18" height="18"></iconify-icon>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center">Tidak ada data anggota yang tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</section>

<!-- Modal Tambah -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-xl sm:max-w-lg md:max-w-md overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-bold mb-4">Tambah Anggota</h2>
        <form id="eventForm" action="{{ route('anggota.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nim" class="block text-gray-700 mb-1">NIM</label>
                <input type="number" id="nim" name="nim" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label for="nama" class="block text-gray-700 mb-1">Nama</label>
                <input type="text" id="nama" name="nama" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label for="angkatan" class="block text-gray-700 mb-1">Angkatan</label>
                <input type="number" id="angkatan" name="angkatan" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label for="status_anggota" class="block text-gray-700 mb-1">Status</label>
                <select id="status_anggota" name="status_anggota" class="w-full border px-3 py-2 rounded" required>
                    <option value="Anggota Tetap">Anggota Tetap</option>
                    <option value="Anggota Muda">Anggota Muda</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEventModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-xl sm:max-w-lg md:max-w-md overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-bold mb-4">Edit Anggota</h2>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_nim" class="block text-gray-700 mb-1">NIM</label>
                <input type="number" id="edit_nim" name="nim" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label for="edit_nama" class="block text-gray-700 mb-1">Nama</label>
                <input type="text" id="edit_nama" name="nama" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label for="edit_angkatan" class="block text-gray-700 mb-1">Angkatan</label>
                <input type="number" id="edit_angkatan" name="angkatan" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label for="edit_status_anggota" class="block text-gray-700 mb-1">Status</label>
                <select id="edit_status_anggota" name="status_anggota" class="w-full border px-3 py-2 rounded" required>
                    <option value="Anggota Tetap">Anggota Tetap</option>
                    <option value="Anggota Muda">Anggota Muda</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEventModal() {
        document.getElementById("eventModal").classList.remove("hidden");
    }

    function closeEventModal() {
        document.getElementById("eventModal").classList.add("hidden");
    }

    function openEditModal(id, nim, nama, angkatan, status) {
        const form = document.getElementById("editForm");
        form.action = "/anggota/" + id;

        document.getElementById("edit_nim").value = nim;
        document.getElementById("edit_nama").value = nama;
        document.getElementById("edit_angkatan").value = angkatan;
        document.getElementById("edit_status_anggota").value = status;

        document.getElementById("editModal").classList.remove("hidden");
    }

    function closeEditModal() {
        document.getElementById("editModal").classList.add("hidden");
    }

    function submitImportForm() {
        document.getElementById('importForm').submit();
    }
</script>
@endsection
