@extends('layouts.app')
@section('content')
<main class="min-h-screen bg-gradient-to-b from-green-100 to-green-300 pt-20 pb-20 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-center text-2xl font-bold text-green-800 mb-8">DAFTAR ACARA</h1>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($acara as $event)
                <div class="bg-white shadow-md rounded-md p-4 text-black">
                    <img src="{{ asset($event->foto) }}" alt="Foto Acara" class="w-full h-48 object-cover rounded">
                    
                    <h2 class="text-xl font-bold mt-2">{{ $event->judul_acara }}</h2>
                    <p class="text-sm text-gray-600">Lokasi: {{ $event->lokasi }}</p>
                    <p class="text-sm text-gray-600">Waktu: 
                        {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y H:i') }} 
                        - {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-600">Ketuplak: {{ $event->ketuplak }}</p>
                    <p class="text-sm text-gray-600">Deskripsi: {{ $event->deskripsi }}</p>
                    <div class="flex justify-center">
                        <a href="{{ route('halamandetail', $event->id) }}" class="mt-3 bg-yellow-400 text-white px-4 py-2 rounded text-sm">DETAIL</a>
                    </div>
                </div>
            @empty
                <p class="text-center col-span-full">Tidak ada acara</p>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $acara->links() }}
        </div>
    </div>
</main>
@endsection


@section('styles')
<style>
/* Styling scroll pada mobile */
.scrollable-menu {
    max-height: 70vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #888 #ddd;
}
.scrollable-menu::-webkit-scrollbar {
    width: 8px;
}

.scrollable-menu::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.scrollable-menu::-webkit-scrollbar-track {
    background-color: #ddd;
    border-radius: 4px;
}
</style>
@endsection
