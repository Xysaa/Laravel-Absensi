@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 flex items-center justify-center px-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
    <h2 class="text-3xl font-bold text-green-800 text-center mb-8">Daftar Akun Baru</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
      @csrf

      {{-- Nama Lengkap --}}
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
        <div class="mt-1 relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <!-- Heroicon user -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.763 0 5.314.894 7.379 2.396M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </span>
          <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name') }}"
            required
            autocomplete="name"
            placeholder="Nama Lengkap"
            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
          >
        </div>
        @error('name')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <div class="mt-1 relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <!-- Heroicon mail -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v8z"/>
            </svg>
          </span>
          <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            placeholder="nama@contoh.com"
            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
          >
        </div>
        @error('email')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      {{-- Username --}}
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <div class="mt-1 relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <!-- Heroicon user -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.763 0 5.314.894 7.379 2.396M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </span>
          <input
            id="username"
            name="username"
            type="text"
            value="{{ old('username') }}"
            required
            autocomplete="username"
            placeholder="username"
            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
          >
        </div>
        @error('username')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password --}}
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <div class="mt-1 relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <!-- Heroicon lock-closed -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 11V7a4 4 0 018 0v4"/>
            </svg>
          </span>
          <input
            id="password"
            name="password"
            type="password"
            required
            placeholder="••••••••"
            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
          >
          <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <!-- Heroicon eye -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
        </div>
        <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter</p>
        @error('password')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div>
        <button
          type="submit"
          class="w-full py-2 font-semibold rounded-lg bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
        >
          Daftar
        </button>
      </div>

      {{-- Divider --}}
      <div class="flex items-center my-4">
        <hr class="flex-grow border-gray-300">
        <span class="mx-2 text-gray-500 text-sm">atau</span>
        <hr class="flex-grow border-gray-300">
      </div>

      {{-- Link ke Login --}}
      <p class="text-center text-sm text-gray-600">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Masuk</a>
      </p>
    </form>
  </div>
</div>
@endsection
