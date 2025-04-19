@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.attendances.index') }}" class="text-blue-600 hover:text-blue-800">
            &larr; Kembali ke Daftar Acara
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $event->title }}</h1>
            <p class="text-gray-600 mb-4">{{ $event->description }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500 mb-4">
                <div>
                    <span class="font-medium text-gray-700">Waktu Mulai:</span> 
                    {{ $event->start_time->format('d M Y, H:i') }}
                </div>
                <div>
                    <span class="font-medium text-gray-700">Waktu Selesai:</span> 
                    {{ $event->end_time->format('d M Y, H:i') }}
                </div>
                <div>
                    <span class="font-medium text-gray-700">Status:</span> 
                    @if($event->isInProgress())
                        <span class="text-green-600">Sedang Berlangsung</span>
                    @elseif($event->is_active && $event->start_time->isFuture())
                        <span class="text-blue-600">Akan Datang</span>
                    @elseif($event->is_active && $event->end_time->isPast())
                        <span class="text-gray-600">Selesai</span>
                    @else
                        <span class="text-red-600">Tidak Aktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-800">
                Data Kehadiran ({{ $attendances->count() }} mahasiswa)
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Absen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($attendances as $index => $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $attendance->student->nim }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $attendance->student->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $attendance->student->major }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $attendance->check_in_time->format('d M Y, H:i:s') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Belum ada mahasiswa yang melakukan absensi pada acara ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
            <a href="{{ route('admin.attendances.index') }}" class="text-blue-600 hover:text-blue-800">
                &larr; Kembali
            </a>
            
            <!-- Export button (implementasi lebih lanjut diperlukan) -->
            <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Export Data (Excel/CSV)
            </a>
        </div>
    </div>
</div>
@endsection