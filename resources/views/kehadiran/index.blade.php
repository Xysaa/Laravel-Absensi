@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 p-4">
            <h1 class="text-white text-2xl font-bold text-center">Sistem Absensi Mahasiswa</h1>
        </div>
        
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($activeEvent)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $activeEvent->title }}</h2>
                    <p class="text-gray-600 mb-2">{{ $activeEvent->description }}</p>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Mulai: {{ $activeEvent->start_time->format('d M Y, H:i') }}</span>
                        <span>Selesai: {{ $activeEvent->end_time->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <form action="{{ route('kehadiran.record') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
                    
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" name="nim" id="nim" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan NIM Anda">
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Submit Absensi
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-10">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Sedang tidak ada acara berlangsung</h3>
                    <p class="mt-1 text-gray-500">Tidak ada acara atau kegiatan yang sedang berlangsung saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection