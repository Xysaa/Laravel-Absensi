<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Mahasiswa Himpunan</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Additional Styles -->
    <style>
        /* Custom styles can be added here */
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-blue-800 text-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="{{ url('/') }}" class="text-xl font-bold">Sistem Absensi Mahasiswa</a>
                
                <!-- Navigation Menu -->
                <nav class="space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-white hover:text-blue-200">Register</a>
                        @endif
                    @else
                        <a href="{{ route('kehadiran.index') }}" class="text-white hover:text-blue-200">Absensi</a>
                        <a href="{{ route('acara.index') }}" class="text-white hover:text-blue-200">Acara</a>
                        
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.kehadiran.index') }}" class="text-white hover:text-blue-200">Laporan Kehadiran</a>
                            <a href="{{ route('anggota.index') }}" class="text-white hover:text-blue-200">Data Mahasiswa</a>
                        @endif
                        
                        <div class="relative inline-block text-left" x-data="{ open: false }">
                            <button @click="open = !open" class="text-white hover:text-blue-200 focus:outline-none">
                                {{ Auth::user()->name }} <span class="ml-1">▼</span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    {{-- <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                     --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </nav>
            </div>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-white py-4 mt-8 border-t">
        <div class="container mx-auto px-4 text-center text-gray-600 text-sm">
            &copy; {{ date('Y') }} TIM 1 ABSENSI
        </div>
    </footer>

    <!-- Alpine.js for dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>