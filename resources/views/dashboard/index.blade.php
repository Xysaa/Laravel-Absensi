<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="bg-green-100 text-green-900 h-full">
  <!-- Mobile Topbar Responsive-->
  <div class="sm:hidden bg-green-900 text-white flex items-center justify-between p-4 fixed top-0 w-full z-40">
    <h1 class="text-lg font-bold">AbsenIF</h1>
    <button @click="sidebarOpen = !sidebarOpen">
      <iconify-icon icon="heroicons:bars-3" width="28" height="28"></iconify-icon>
    </button>
  </div>
  <!-- Sidebar -->
  <div>
    <aside
      class="fixed top-0 left-0 z-40 h-full w-64 bg-green-900 text-white transform transition-transform duration-300 ease-in-out sm:translate-x-0"
      :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
    >
      <div class="p-6 flex flex-col h-full">
        <div class="flex items-center justify-between sm:block mb-6">
          <h2 class="text-2xl font-bold">AbsenIF</h2>
          <button @click="sidebarOpen = false" class="sm:hidden">
            <iconify-icon icon="heroicons:x-mark" width="24" height="24"></iconify-icon>
          </button>
        </div>
        <nav>
          <ul class="space-y-4">
            <li><a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 bg-gray-700 px-4 py-2 rounded"><iconify-icon icon="heroicons:computer-desktop-16-solid" width="20" height="20"></iconify-icon>Dashboard</a></li>
            <li><a href="{{ route('acara.index') }}" class="flex items-center gap-2 hover:bg-gray-700 px-4 py-2 rounded"><iconify-icon icon="heroicons:newspaper" width="20" height="20"></iconify-icon>Acara</a></li>
            <li><a href="{{ route('anggota.index') }}" class="flex items-center gap-2 hover:bg-gray-700 px-4 py-2 rounded"><iconify-icon icon="heroicons:pencil-square-16-solid" width="20" height="20"></iconify-icon>Anggota</a></li>
            <li><a href="{{ route('user.index') }}" class="flex items-center gap-2 hover:bg-gray-700 px-4 py-2 rounded"><iconify-icon icon="heroicons:user-group-16-solid" width="20" height="20"></iconify-icon>Users</a></li>
            <li><a href="{{ route('kehadiran.index') }}" class="flex items-center gap-2 hover:bg-gray-700 px-4 py-2 rounded"><iconify-icon icon="heroicons:qr-code" width="20" height="20"></iconify-icon>Scan</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 hover:bg-gray-700 px-4 py-2 rounded w-full text-left">
                  <iconify-icon icon="heroicons:arrow-left-end-on-rectangle-16-solid" width="20" height="20"></iconify-icon>Logout
                </button>
              </form>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
<!-- Overlay Mobile -->
<div 
  class="fixed inset-0 bg-black bg-opacity-50 z-30 sm:hidden"
  x-show="sidebarOpen"
  @click="sidebarOpen = false"
  x-transition.opacity
></div>
  </div>
  <!-- Main content pake clasnnya -->
  <div class="pt-16 sm:pt-0 sm:ml-64 p-4 transition-all">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <header class="bg-white p-4 rounded shadow mb-6">
        <h1 class="text-2xl font-semibold">DASHBOARD</h1>
      </header>
  <!-- Cards -->
  <section class="bg-white p-6 rounded shadow">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="{{ route('kehadiran.index') }}" class="bg-blue-100 hover:bg-blue-200 p-6 rounded shadow text-center transition">
        <iconify-icon icon="fluent-color:scan-person-16" width="20" height="20"></iconify-icon>
        <p class="text-sm text-blue-700">Scan QR absensi</p>
      </a>
      <a href="{{ route('anggota.index') }}" class="bg-green-100 hover:bg-green-200 p-6 rounded shadow text-center transition">
        <iconify-icon icon="fluent-color:people-add-20" width="20" height="20"></iconify-icon>
        <p class="text-sm text-green-700">Kelola Anggota</p>
      </a>
      <a href="{{ route('acara.index') }}" class="bg-yellow-100 hover:bg-yellow-200 p-6 rounded shadow text-center transition">
        <iconify-icon icon="fluent-color:calendar-edit-32" width="20" height="20"></iconify-icon>
        <p class="text-sm text-yellow-700">Kelola Acara</p>
      </a>
    </div>
  </section>
</div>
  </div>
</body>
</html>