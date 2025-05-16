@extends('layouts.app')

@section('styles')
<style>
    main {
        min-height: 100vh;
        padding: 6rem 1rem 4rem; /* untuk menghindari navbar dan footer */
        display: flex;
        justify-content: center;
        align-items: center;
        background: no-repeat center fixed;
        background-image: linear-gradient(rgba(0, 128, 0, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset('img/back.png') }}');
        background-size: cover;
    }

    .card {
        max-width: 600px;
        width: 100%;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
        color: black !important;
    }

    textarea {
        resize: none;
    }
</style>
@endsection


@section('content')
<main>
    <div class="card">
        <div class="flex justify-center mb-4">
            <img src="{{ asset($acara->foto ?? 'img/default.jpg') }}" alt="Poster Acara" class="w-full h-56 sm:h-72 object-cover rounded-md" />
        </div>
        <h2 class="text-center text-xl font-semibold mb-4">DETAIL ACARA</h2>
        <div class="space-y-3">
            <p><strong>Nama Acara:</strong> {{ $acara->judul_acara ?? 'Tidak tersedia' }}</p>
            <p><strong>Ketuplak:</strong> {{ $acara->ketuplak ?? 'Tidak tersedia' }}</p>
            <p><strong>Tanggal:</strong> 
                {{ $acara->start_time ? \Carbon\Carbon::parse($acara->start_time)->format('d-m-Y') : 'Tidak tersedia' }} 
                s/d 
                {{ $acara->end_time ? \Carbon\Carbon::parse($acara->end_time)->format('d-m-Y') : 'Tidak tersedia' }}
            </p>
            <p><strong>Lokasi:</strong> {{ $acara->lokasi ?? 'Tidak tersedia' }}</p>
            <p><strong>Deskripsi:</strong></p>
            <textarea class="w-full h-28 p-2 border border-gray-300 rounded-md" disabled>{{ $acara->deskripsi ?? 'Tidak ada deskripsi' }}</textarea>
        </div>
    </div>
</main>
@endsection
