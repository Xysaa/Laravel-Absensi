@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Acara</h1>
        @if(Auth::user()->is_admin === true)
        <a href="{{ route('acara.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Buat Acara Baru
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        @if(Auth::user()->is_admin === true)<th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>@endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($acara as $a)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $a->judul_acara }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $a->start_time->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $a->end_time->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($a->isInProgress())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sedang Berlangsung
                                </span>
                            @elseif($a->is_active && $a->start_time->isFuture())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Akan Datang
                                </span>
                            @elseif($a->is_active && $a->end_time->isPast())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Selesai
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        @if(Auth::user()->is_admin === true)
                        <td class="px-6escaped_string py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('acara.show', $a) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                <a href="{{ route('acara.edit', $a) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('acara.destroy', $a) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus acara ini?')">Hapus</button>
                                </form>
                            </td>
                            @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada acara yang tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3">
            {{ $acara->links() }}
        </div>
    </div>
</div>
@endsection