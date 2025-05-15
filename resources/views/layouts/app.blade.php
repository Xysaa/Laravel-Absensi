<!DOCTYPE html>
<html lang="en" x-data="carousel()" x-init="start()">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>@yield('title', 'Web Absensi HMIF')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  @yield('styles')
</head>
<body class="flex flex-col min-h-screen text-white">
  <!-- Navbar tetap di atas -->
  <div class="bg-green-800 text-white px-4 py-3 flex justify-between items-center fixed top-0 left-0 w-full z-50 shadow-md">
    <div class="flex items-center">
      <img src="{{ asset('img/HMIF.png') }}" alt="Logo" class="h-8 w-8 sm:h-10 sm:w-10" />
    </div>
    <h1 class="text-xs sm:text-lg md:text-xl font-bold text-center flex-grow">WEB ABSENSI HMIF</h1>
    <div class="w-8 sm:w-10"></div> <!-- Spacer for balance -->
  </div>
@yield('content')
  <!-- Footer tetap di bawah -->
  <footer class="bg-green-800 p-4 text-center text-white text-sm fixed bottom-0 left-0 w-full z-50">
    <p>&copy; 2025 HMIF - Web Absensi</p>
  </footer>
  <!-- Scripts -->
@yield('scripts')
</body>
</html>