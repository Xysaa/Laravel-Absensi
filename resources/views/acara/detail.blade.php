@extends('layouts.app')

@section('content')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Acara: {{ $acara->judul_acara }}</h1>
        </div>

        <!-- Card Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-md shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Anggota Hadir</h2>
                <p class="text-2xl font-bold text-blue-600">{{ $totalHadir }}</p>
            </div>
            <div class="bg-white p-4 rounded-md shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Anggota Tidak Hadir</h2>
                <p class="text-2xl font-bold text-blue-600">{{ $totalTidakHadir }}</p>
            </div>
            <div class="bg-white p-4 rounded-md shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Keseluruhan Anggota</h2>
                <p class="text-2xl font-bold text-blue-600">{{ $totalAnggota }}</p>
            </div>
        </div>

        <!-- Tabel Kehadiran -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Acara</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kehadiran as $index => $hadir)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $acara->judul_acara }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $hadir->anggota->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Tidak ada anggota yang hadir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('acara.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                Kembali
            </a>
            <a href="{{ route('acara.exportCsv', $acara) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Ekspor CSV
            </a>
        </div>
    </div>
</div>
@endsection