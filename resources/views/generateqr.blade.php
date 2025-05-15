@extends('layouts.app')
@section('title', 'Generate QR - HMIF')
@section('styles')
<style>
    body, html {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: no-repeat center center fixed;
        background-image: linear-gradient(rgba(0, 128, 0, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset('img/back.png') }}');
        background-size: cover;
    }
    main {
        flex: 1;
    }
</style>
@endsection
@section('content')
<!-- Main Content -->
<main class="flex-grow flex flex-col items-center justify-center px-4 py-8 text-black">
    <div class="bg-white bg-opacity-90 rounded-xl shadow-lg p-6 max-w-md w-full">
        <h2 class="text-xl sm:text-2xl font-bold text-green-800 mb-4 text-center">Generate QR Code</h2>
    <!-- Show error messages if any -->
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Input Form -->
    <form action="{{ route('generate.qr') }}" method="POST" class="flex flex-col gap-4">
        @csrf
        <input
            type="text"
            name="nim"
            placeholder="Masukkan NIM"
            class="p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600 text-black"
            required
        />
        <button
            type="submit"
            class="bg-green-800 text-white py-2 rounded-lg font-semibold hover:bg-green-700 transition"
        >
            Generate QR
        </button>
    </form>
    
    <!-- QR Code Display -->
    @isset($qrCode)
        <div class="mt-6 text-center">
            <p class="mb-2 text-green-700 break-all">QR untuk: <strong>{{ $anggota->nama }}</strong> ({{ $anggota->nim }})</p>
            <div class="inline-block p-2 bg-white border rounded-lg">
                {!! $qrCode !!}
            </div>
        </div>
    @endisset
</div>
</main>
@endsection
@section('header-extra')
{{-- <a href="{{ route('generate.qr') }}" class="bg-gray-700 px-2 py-1 rounded hover:bg-gray-600 text-xs sm:text-base">Back</a> --}}
@endsection