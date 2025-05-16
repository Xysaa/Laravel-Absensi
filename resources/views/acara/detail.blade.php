@extends('layouts.admin')

@section('title', 'Detail Acara')

@section('content')
<main class="max-w-4xl mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <!-- Card Statistik -->
    <section class="bg-white p-6 rounded shadow">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <a class="bg-blue-100 hover:bg-blue-200 p-6 rounded shadow text-center transition">
                <p class="text-sm text-blue-700"><b>{{ $totalHadir }}</b></p>
                <p class="text-sm text-blue-700">Total Hadir</p>
            </a>

            <a class="bg-green-100 hover:bg-green-200 p-6 rounded shadow text-center transition">
                <p class="text-sm text-green-700"><b>{{ $totalAnggota }}</b></p>
                <p class="text-sm text-green-700">Total Anggota</p>
            </a>
            
            <a class="bg-yellow-100 hover:bg-yellow-200 p-6 rounded shadow text-center transition">
                <p class="text-sm text-yellow-700"><b>{{ $totalTidakHadir }}</b></p>
                <p class="text-sm text-yellow-700">Tidak Hadir</p>
            </a>
        </div>
    </section>

    <!-- Tombol Cetak -->
    <div class="flex justify-end mt-4">
        <a href="{{ route('acara.exportCsv', $acara) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded shadow transition ml-2">
            <iconify-icon icon="heroicons:document-arrow-down" class="mr-2" width="20" height="20"></iconify-icon>
            Ekspor CSV
        </a>
    </div>

    <br>

    <div class="space-y-4">
        <div>
            <strong>Nama Acara:</strong>
            <p>{{ $acara->judul_acara }}</p>
        </div>
        <div>
            <strong>Ketua Pelaksana:</strong>
            <p>{{ $acara->ketuplak }}</p>
        </div>
        <div>
            <strong>Tanggal:</strong>
            <p>{{ \Carbon\Carbon::parse($acara->tanggal)->format('d F Y') }}</p>
        </div>

        <div>
            <strong>Deskripsi:</strong>
            <p>{{ $acara->deskripsi }}</p>
        </div>
        @if ($acara->foto)
            <div class="mt-4">
                <label class="font-semibold">Foto Acara:</label>
                <div class="mt-2">
                    <img src="{{ asset($acara->foto) }}" alt="Foto Acara" class="rounded border w-full max-w-md">
                </div>
            </div>
        @endif

    </div>

    <!-- Tabel Anggota -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Daftar Anggota yang Hadir</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded shadow">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">NIM</th>
                        <th class="px-4 py-2 border">Angkatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kehadiran as $index => $hadir)
                    <tr>
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $hadir->anggota->nama }}</td>
                        <td class="px-4 py-2 border">{{ $hadir->anggota->nim }}</td>
                        <td class="px-4 py-2 border">{{ $hadir->anggota->angkatan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 border text-center">Tidak ada anggota yang hadir</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('acara.index') }}" class="inline-flex items-center text-blue-600 hover:underline">
            <iconify-icon icon="heroicons:arrow-left" class="mr-2" width="20"></iconify-icon>
            Kembali ke daftar acara
        </a>
    </div>
</main>
@endsection