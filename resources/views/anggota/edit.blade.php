@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Mahasiswa</h1>
            <a href="{{ route('anggota.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                Kembali ke Daftar
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('anggota.update', $anggota->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
               
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input type="text" name="nim" id="nim" value="{{ old('nim', $anggota->nim) }}" required
                           class="w-full px-4 py-2 border @error('nim') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
               
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $anggota->nama) }}" required
                           class="w-full px-4 py-2 border @error('nama') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
               
                <div>
                    <label for="status_anggota" class="block text-sm font-medium text-gray-700 mb-1">Status Anggota</label>
                    <input type="text" name="status_anggota" id="status_anggota" value="{{ old('status_anggota', $anggota->status_anggota) }}" required
                           class="w-full px-4 py-2 border @error('status_anggota') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('status_anggota')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
               
                <div>
                    <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                    <input type="number" name="angkatan" id="angkatan" value="{{ old('angkatan', $anggota->angkatan) }}" required 
                           min="2000" max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-2 border @error('angkatan') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('angkatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
               
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('anggota.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 bg-red-50 border-t-4 border-red-500">
                <h2 class="text-lg font-semibold text-red-800 mb-4">Hapus Data Mahasiswa</h2>
                <p class="text-red-700 mb-4">Penghapusan data bersifat permanen dan tidak dapat dibatalkan.</p>
                <form action="{{ route('anggota.destroy', $anggota) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? Penghapusan tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Hapus Data Mahasiswa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection