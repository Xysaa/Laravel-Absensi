@extends('layouts.app')
@section('title', 'Web Absensi HMIF')
@section('styles')
<style>
    body, html {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: no-repeat center fixed;
        background-image: linear-gradient(rgba(0, 128, 0, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset('img/back.png') }}');
        background-size: cover;
    }
    main {
        flex: 1;
    }
</style>
@endsection
@section('content')
<!-- Konten utama -->
<main class="flex-grow flex flex-col items-center justify-center px-4 py-6 pt-20 pb-24 text-black">
    <!-- Slideshow -->
    <div class="relative w-full max-w-[600px] aspect-[3/2] rounded-lg overflow-hidden mb-6 shadow-lg">
        <template x-for="(img, index) in images" :key="index">
            <img :src="img"
                x-show="current === index"
                class="absolute w-full h-full object-cover transition-opacity duration-700"
                x-transition.opacity />
        </template>
    </div>
    <!-- Dots Navigation -->
    <div class="flex space-x-2 mb-8">
        <template x-for="(img, index) in images" :key="index">
            <button
                @click="current = index"
                :class="current === index ? 'bg-blue-800 scale-110' : 'bg-gray-400'"
                class="w-4 h-4 rounded-full focus:outline-none transition duration-300 transform">
            </button>
        </template>
    </div>
    <!-- Tombol Absen dan Acara -->
    <div class="flex flex-row flex-wrap gap-4 sm:gap-12 justify-center items-center w-full px-4">
        <!-- Absen -->
        <a href="{{ route('generateqr') }}" class="flex flex-col items-center bg-white rounded-xl p-4 shadow-lg hover:scale-105 transition-transform duration-300 w-36 sm:w-40">
            <img src="{{ asset('img/absen.png') }}" alt="Foto Absen" class="w-[80px] h-[80px] object-cover rounded-lg mb-2" />
            <span class="text-green-800 font-semibold text-base sm:text-lg">Absen</span>
        </a>
        <!-- Acara -->
        <a href="{{ route('halamanacara') }}" class="flex flex-col items-center bg-white rounded-xl p-4 shadow-lg hover:scale-105 transition-transform duration-300 w-36 sm:w-40">
            <img src="{{ asset('img/acara.png') }}" alt="Foto Acara" class="w-[80px] h-[80px] object-cover rounded-lg mb-2" />
            <span class="text-green-800 font-semibold text-base sm:text-lg">Acara</span>
        </a>
    </div>
</main>
@endsection
@section('scripts')
<script>
    function carousel() {
        return {
            current: 0,
            images: [
                '{{ asset('img/1.jpg') }}',
                '{{ asset('img/2.jpg') }}',
                '{{ asset('img/3.jpg') }}',
            ],
            start() {
                setInterval(() => {
                    this.current = (this.current + 1) % this.images.length;
                }, 4000);
            }
        }
    }
</script>
@endsection