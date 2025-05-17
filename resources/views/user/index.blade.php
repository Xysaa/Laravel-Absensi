@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div x-data="{ 
  showAddModal: false, 
  showEditModal: false,
  editUserId: null,
  editUsername: '',
  editEmail: '',
  editIsAdmin: 0
}">
  <header class="bg-white p-4 rounded shadow mb-6">
    <h1 class="text-xl font-semibold">Daftar User</h1>
  </header>

  <section class="bg-white p-6 rounded shadow">
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
      </div>
    @elseif(session('error'))
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
      </div>
    @endif

    <div class="flex justify-between items-center mb-4">
      <button type="button" @click="showAddModal = true" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        <iconify-icon icon="line-md:clipboard-plus" width="20" height="20"></iconify-icon>
        Tambah User
      </button>
      <input @keyup="filterTable($event.target)" type="text" placeholder="Cari User..." class="px-3 py-2 border rounded w-1/3">
    </div>

    <div class="w-full overflow-x-auto">
      <table id="userTable" class="min-w-full bg-white border rounded shadow text-sm sm:table block">
        <thead class="bg-gray-200 text-gray-700 sm:table-header-group">
          <tr class="sm:table-row">
            <th class="text-left px-4 py-2 border-b">NO</th>
            <th class="text-left px-4 py-2 border-b">Username</th>
            <th class="text-left px-4 py-2 border-b">Role</th>
            <th class="text-left px-4 py-2 border-b">Aksi</th>
          </tr>
        </thead>
        <tbody class="sm:table-row-group">
          @forelse($users as $index => $user)
          <tr class="sm:table-row border-b">
            <td class="sm:table-cell px-4 py-2 font-semibold">{{ $index + 1 }}</td>
            <td class="sm:table-cell px-4 py-2">{{ $user->name }}</td>
            <td class="sm:table-cell px-4 py-2">{{ $user->is_admin ? 'Admin' : 'Petugas' }}</td>
            <td class="sm:table-cell px-4 py-2">
              <div class="flex gap-2 mt-2 sm:mt-0">
                <button type="button" @click="
                  editUserId = '{{ $user->id }}'; 
                  editUsername = '{{ $user->name }}'; 
                  editIsAdmin = '{{ $user->is_admin }}';
                  editEmail = '{{ $user->email }}'; 
                  showEditModal = true;
                " class="flex items-center gap-2 bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                  <iconify-icon icon="line-md:clipboard-list" width="20" height="20"></iconify-icon>
                </button>
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition gap-2"
                    onclick="return confirm('Anda yakin ingin menghapus user ini?')">
                    <iconify-icon icon="line-md:trash" width="20" height="20"></iconify-icon>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-4 py-2 text-center">Tidak ada data user.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  
    <!-- Modal Tambah -->
    <div x-show="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         x-transition.opacity>
      <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-xl overflow-y-auto max-h-[90vh]"
           @click.outside="showAddModal = false">
        <h2 class="text-xl font-bold mb-4">Tambah User</h2>
        <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label class="block text-gray-700 mb-1">Username</label>
            <input type="text" name="username" class="w-full border px-3 py-2 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Role</label>
            <select name="is_admin" class="w-full border px-3 py-2 rounded" required>
              <option value="0">Petugas</option>
              <option value="1">Admin</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         x-transition.opacity>
      <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-xl overflow-y-auto max-h-[90vh]"
           @click.outside="showEditModal = false">
        <h2 class="text-xl font-bold mb-4">Edit User</h2>
        <form x-bind:action="'{{ url('user') }}/' + editUserId" method="POST" class="space-y-4">
          @csrf
          @method('PUT')
          <div>
            <label class="block text-gray-700 mb-1">Username</label>
            <input type="text" name="username" x-model="editUsername" class="w-full border px-3 py-2 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded" placeholder="Kosongkan jika tidak diubah">
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Email</label>
            <input type="email" name="email" x-model="editEmail" class="w-full border px-3 py-2 rounded" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Role</label>
            <select name="is_admin" x-model="editIsAdmin" class="w-full border px-3 py-2 rounded" required>
              <option value="0">Petugas</option>
              <option value="1">Admin</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
<script>
  function filterTable(input) {
    const filter = input.value.toUpperCase();
    const table = document.getElementById("userTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
      const td = tr[i].getElementsByTagName("td");
      if (td.length > 0) {
        const txtValue = td[1].textContent || td[1].innerText;
        tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
      }
    }
  }

  // Add debug console log to check if data is properly set
  document.addEventListener('alpine:init', () => {
    Alpine.data('debugData', () => ({
      init() {
        console.log('Alpine initialized');
      }
    }));
  });
</script>
@endsection
