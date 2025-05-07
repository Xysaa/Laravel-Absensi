@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 flex items-center justify-center px-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
    <h2 class="text-3xl font-bold text-green-800 text-center mb-8">Masuk</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
      @csrf

      {{-- Email --}}
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <div class="mt-1 relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <!-- Heroicon mail -->
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v8z" />
            </svg>
          </span>
          <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            required
            autofocus
            placeholder="nama@contoh.com"
            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
          >
        </div>
        @error('email')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password --}}
      {{-- Password --}}
<div>
    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
    <div class="mt-1 relative">
      <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <!-- Heroicon lock-closed outline -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <!-- body -->
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
          <!-- shackle -->
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 11V7a4 4 0 018 0v4" />
        </svg>
      </span>
      <input
        id="password"
        name="password"
        type="password"
        required
        placeholder="••••••••"
        class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
      >
    </div>
    @error('password')
      <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
  </div>
  

      {{-- Remember & Forgot --}}
      <div class="flex items-center justify-between">
        <label class="inline-flex items-center">
          <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 border-gray-300 rounded">
          <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
        </label>
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">
            Lupa password?
          </a>
        @endif
      </div>

      {{-- Submit --}}
      <div>
        <button
          type="submit"
          class="w-full py-2 font-semibold rounded-lg bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
        >
          Masuk
        </button>
      </div>

      {{-- Divider --}}
      <div class="flex items-center my-4">
        <hr class="flex-grow border-gray-300">
        <span class="mx-2 text-gray-500 text-sm">atau</span>
        <hr class="flex-grow border-gray-300">
      </div>

      {{-- Register link --}}
      <p class="text-center text-sm text-gray-600">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Daftar sekarang</a>
      </p>
    </form>
  </div>
</div>
@endsection
