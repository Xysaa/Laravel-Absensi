@extends('layouts.admin')
@section('title', 'Daftar Acara')
@section('content')
<header class="bg-white p-4 rounded shadow mb-6">
  <h1 class="text-xl font-semibold">Daftar Acara</h1>
</header>

<section class="bg-white p-6 rounded shadow">
  <div class="flex justify-between items-center mb-4">
    @if(Auth::user()->is_admin === true)
    <button onclick="openEventModal()" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
      <iconify-icon icon="line-md:clipboard-plus" width="20" height="20"></iconify-icon> Tambah Acara
    </button>
    @endif
    <input onkeyup="filterTable(this)" type="text" placeholder="Cari acara..." class="px-3 py-2 border rounded w-1/3">
  </div>

  @if (session('success'))
  <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
    <p>{{ session('success') }}</p>
  </div>
  @endif

  <div class="w-full overflow-x-auto">
    <table id="userTable" class="min-w-full bg-white border rounded shadow text-sm sm:table block">
      <thead class="bg-gray-200 text-gray-700 sm:table-header-group">
        <tr class="sm:table-row">
          <th class="text-left px-4 py-2 border-b">Judul</th>
          <th class="text-left px-4 py-2 border-b">Waktu Mulai</th>
          <th class="text-left px-4 py-2 border-b">Waktu Selesai</th>
          <th class="text-left px-4 py-2 border-b">Status</th>
          @if(Auth::user()->is_admin === true)
          <th class="text-left px-4 py-2 border-b">Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody class="sm:table-row-group">
        @forelse ($acara as $a)
        <tr class="sm:table-row border-b">
          <td class="sm:table-cell px-4 py-2">{{ $a->judul_acara }}</td>
          <td class="sm:table-cell px-4 py-2">{{ $a->start_time->format('d M Y, H:i') }}</td>
          <td class="sm:table-cell px-4 py-2">{{ $a->end_time->format('d M Y, H:i') }}</td>
          <td class="sm:table-cell px-4 py-2">
            @if($a->isInProgress())
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sedang Berlangsung</span>
            @elseif($a->is_active && $a->start_time->isFuture())
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Akan Datang</span>
            @elseif($a->is_active && $a->end_time->isPast())
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Selesai</span>
            @else
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
            @endif
          </td>
          @if(Auth::user()->is_admin === true)
          <td class="sm:table-cell px-4 py-2">
            <div class="flex gap-2 mt-2 sm:mt-0">
              <a href="{{ route('acara.show', $a) }}" class="inline-flex items-center bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition gap-2">
                <iconify-icon icon="line-md:alert-circle-twotone" width="18" height="18"></iconify-icon> Detail
              </a>
              <button onclick="openEditModal({{ $a->id }})" class="inline-flex items-center bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700 transition gap-2">
                <iconify-icon icon="line-md:clipboard-list" width="18" height="18"></iconify-icon> Edit
              </button>
              <form action="{{ route('acara.destroy', $a) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition gap-2"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus acara ini?')">
                  <iconify-icon icon="line-md:trash" width="18" height="18"></iconify-icon> Hapus
                </button>
              </form>
              @if($a->isInProgress())
              <a href="{{ route('kehadiran.index', ['event_id' => $a->id]) }}" class="inline-flex items-center bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition gap-2">
                <iconify-icon icon="heroicons:qr-code" width="18" height="18"></iconify-icon> Scan
              </a>
              @endif
            </div>
          </td>
          @endif
        </tr>

        <div id="editModal-{{ $a->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
          <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-xl overflow-y-auto max-h-[90vh]">
            <h2 class="text-xl font-bold mb-4">Edit Acara</h2>
            <form action="{{ route('acara.update', $a) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
              @csrf
              @method('PUT')
              <div>
                <label class="block text-gray-700 mb-1">Judul Acara</label>
                <input type="text" name="judul_acara" value="{{ $a->judul_acara }}" class="w-full border px-3 py-2 rounded" required>
              </div>
              <div>
                <label class="block text-gray-700 mb-1">Ketua Pelaksana</label>
                <input type="text" name="ketua_pelaksana" value="{{ $a->ketuplak }}" class="w-full border px-3 py-2 rounded" required>
              </div>
              <div>
                <label class="block text-gray-700 mb-1">Lokasi</label>
                <input type="text" name="lokasi" value="{{ $a->lokasi }}" class="w-full border px-3 py-2 rounded" required>
              </div>
              <div>
                <label class="block text-gray-700 mb-1">Ganti Foto (opsional)</label>
                <input type="file" name="foto" class="w-full border px-3 py-2 rounded bg-white" accept="image/*">
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-gray-700 mb-1">Waktu Mulai</label>
                  <input type="datetime-local" name="start_time" value="{{ $a->start_time->format('Y-m-d\TH:i') }}" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div>
                  <label class="block text-gray-700 mb-1">Waktu Berakhir</label>
                  <input type="datetime-local" name="end_time" value="{{ $a->end_time->format('Y-m-d\TH:i') }}" class="w-full border px-3 py-2 rounded" required>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="aktif-{{ $a->id }}" value="1" class="h-4 w-4 text-green-600 border-gray-300 rounded"
                  {{ $a->is_active ? 'checked' : '' }}>
                <label for="aktif-{{ $a->id }}" class="text-gray-700">Aktifkan Acara</label>
              </div>
              <div>
                <label class="block text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border px-3 py-2 rounded" rows="3">{{ $a->deskripsi }}</textarea>
              </div>
              <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal({{ $a->id }})" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
              </div>
            </form>
          </div>
        </div>
        @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada acara yang tersedia</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="px-4 py-3">
    {{ $acara->links() }}
  </div>
</section>

<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
  <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-xl overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-bold mb-4">Tambah Acara</h2>
    <form action="{{ route('acara.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div>
        <label class="block text-gray-700 mb-1">Judul Acara</label>
        <input type="text" name="judul_acara" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block text-gray-700 mb-1">Ketua Pelaksana</label>
        <input type="text" name="ketua_pelaksana" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block text-gray-700 mb-1">Lokasi</label>
        <input type="text" name="lokasi" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block text-gray-700 mb-1">Foto Acara</label>
        <input type="file" name="foto" class="w-full border px-3 py-2 rounded bg-white" accept="image/*">
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 mb-1">Waktu Mulai</label>
          <input type="datetime-local" name="start_time" class="w-full border px-3 py-2 rounded" required>
        </div>
        <div>
          <label class="block text-gray-700 mb-1">Waktu Berakhir</label>
          <input type="datetime-local" name="end_time" class="w-full border px-3 py-2 rounded" required>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="aktif" value="1" class="h-4 w-4 text-green-600 border-gray-300 rounded">
        <label for="aktif" class="text-gray-700">Aktifkan Acara</label>
      </div>
      <div>
        <label class="block text-gray-700 mb-1">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border px-3 py-2 rounded" rows="3"></textarea>
      </div>
      <div class="flex justify-end gap-3 pt-2">
        <button type="button" onclick="closeEventModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openEventModal() {
    document.getElementById("eventModal").classList.remove("hidden");
  }

  function closeEventModal() {
    document.getElementById("eventModal").classList.add("hidden");
  }

  function openEditModal(id) {
    document.getElementById(`editModal-${id}`).classList.remove("hidden");
  }

  function closeEditModal(id) {
    document.getElementById(`editModal-${id}`).classList.add("hidden");
  }

  function filterTable(input) {
    let filter = input.value.toUpperCase();
    let table = document.getElementById("userTable");
    let tr = table.getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
      let td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        let txtValue = td.textContent || td.innerText;
        tr[i].style.display = txtValue.toUpperCase().includes(filter) ? "" : "none";
      }
    }
  }
</script>
@endsection