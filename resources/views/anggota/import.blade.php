@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Import Data Mahasiswa dari CSV</h1>
        <a href="{{ route('anggota.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Kembali
        </a>
    </div>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    @if (session('debug_info'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-4" role="alert">
            <p class="font-bold">Informasi Debug:</p>
            <ul class="list-disc list-inside mt-2">
                <li>Header terdeteksi: {{ implode(', ', session('debug_info')['detected_header']) }}</li>
                <li>Header yang diharapkan: {{ implode(', ', session('debug_info')['expected_header']) }}</li>
                <li>Jumlah baris: {{ session('debug_info')['rows_count'] }}</li>
                <li>Contoh baris pertama: {{ implode(', ', session('debug_info')['sample_row']) }}</li>
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Petunjuk:</h2>
                <ol class="list-decimal list-inside space-y-1 text-gray-600">
                    <li>File harus berformat CSV (Comma Separated Values)</li>
                    <li>Baris pertama harus berisi header: nim, nama, status_anggota, angkatan</li>
                    <li>NIM harus unik dan belum terdaftar</li>
                    <li>Angkatan harus berupa tahun (min 2000, max tahun depan)</li>
                    <li>Pastikan file disimpan dengan encoding UTF-8</li>
                    <li>Pastikan menggunakan tanda koma (,) sebagai pemisah kolom</li>
                </ol>
            </div>
            
            <div class="mb-6 bg-blue-50 p-4 rounded-md border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold text-blue-700 mb-2">Tips Mengatasi Masalah:</h2>
                <ul class="list-disc list-inside space-y-1 text-blue-600">
                    <li>Jika menggunakan Excel: Pilih "Save As" > "CSV (Comma delimited) (*.csv)"</li>
                    <li>Buka file CSV dengan editor teks (seperti Notepad) untuk memastikan format benar</li>
                    <li>Hapus karakter khusus atau spasi di nama header</li>
                    <li>Pastikan tidak ada baris kosong di awal atau akhir file</li>
                </ul>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Contoh Format CSV:</h2>
                <div class="bg-gray-100 p-4 rounded overflow-x-auto">
                    <pre class="text-sm text-gray-800">nim,nama,status_anggota,angkatan
123140038,Budi Santoso,Aktif,2023
123140039,Siti Aisyah,Aktif,2023
123140040,Ahmad Rizki,Aktif,2023</pre>
                </div>
            </div>

            <form action="{{ route('anggota.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File CSV
                    </label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv, text/csv" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('csv_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection