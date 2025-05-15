@extends('layouts.app')
@section('title', 'Login - Admin')
@section('content')
<div class="bg-gradient-to-tr from-green-400 via-green-500 to-green-600 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white/90 backdrop-blur-sm p-6 sm:p-8 rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md border border-green-200">
    <div class="text-center mb-6">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-green-700">Login Admin</h2>
      <p class="text-green-600 text-sm mt-1">Silakan masuk untuk melanjutkan</p>
    </div>
<form method="POST" action="{{ route('login') }}" class="space-y-4">
  @csrf
  
  {{-- Email --}}
  <div>
    <label for="email" class="block text-green-800 mb-1 font-medium">Email</label>
    <div class="flex items-center border border-green-300 rounded px-3 py-2 bg-white focus-within:ring-2 focus-within:ring-green-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="text-green-600 mr-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v8z" />
      </svg>
      <input 
        id="email" 
        name="email" 
        type="email" 
        value="{{ old('email') }}" 
        required 
        autofocus 
        placeholder="nama@contoh.com" 
        class="w-full bg-transparent text-green-800 placeholder-green-400 focus:outline-none text-sm sm:text-base"
      />
    </div>
    @error('email')
      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
  </div>
  
  {{-- Password --}}
  <div>
    <label for="password" class="block text-green-800 mb-1 font-medium">Password</label>
    <div class="flex items-center border border-green-300 rounded px-3 py-2 bg-white focus-within:ring-2 focus-within:ring-green-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="text-green-600 mr-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 018 0v4" />
      </svg>
      <input 
        id="password" 
        name="password" 
        type="password" 
        required 
        placeholder="••••••••" 
        class="w-full bg-transparent text-green-800 placeholder-green-400 focus:outline-none text-sm sm:text-base"
      />
    </div>
    @error('password')
      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
  </div>
  
  {{-- Remember & Forgot --}}
  <div class="flex items-center justify-between text-sm">
    <label class="inline-flex items-center">
      <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 border-gray-300 rounded">
      <span class="ml-2 text-green-800">Ingat saya</span>
    </label>
    @if (Route::has('password.request'))
      <a href="{{ route('password.request') }}" class="text-green-600 hover:underline">
        Lupa password?
      </a>
    @endif
  </div>
  
  {{-- Submit --}}
  <button 
    type="submit" 
    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-300 text-sm sm:text-base"
  >
    Masuk
  </button>
  
  {{-- Divider --}}

  
  {{-- Register link --}}

</form>
  </div>
</div>
@endsection