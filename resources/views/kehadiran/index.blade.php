@extends('layouts.app')
@section('title', 'Scan Kehadiran')

@section('content')
<div class="container mx-auto p-4">
    @if ($activeEvents && $activeEvents->count() > 0)
        <!-- Jika ada acara aktif, tampilkan dropdown dan scanner -->
        <h1 class="text-2xl font-bold mb-4">Scan QR Code untuk Acara</h1>
        
        <div class="mb-4">
            <label for="event_select" class="block text-gray-700 mb-1">Pilih Acara</label>
            <select id="event_select" name="event_id" class="w-full sm:w-1/2 border px-3 py-2 rounded bg-white text-gray-800 focus:ring focus:ring-green-300 focus:border-green-500" onchange="updateEventId(this)">
                @foreach ($activeEvents as $event)
                    <option value="{{ $event->id }}">{{ $event->judul_acara }} ({{ $event->start_time->format('d M Y, H:i') }})</option>
                @endforeach
            </select>
        </div>

        <div id="reader" width="600px"></div>
        <div id="notification" class="notification" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #4CAF50; color: white; padding: 20px; border-radius: 5px; z-index: 9999; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"></div>

        <form method="POST" action="{{ route('kehadiran.record') }}" id="attendance_form">
            @csrf
            <input type="hidden" name="nim" id="nim">
            <input type="hidden" name="event_id" id="event_id" value="{{ $activeEvents->first()->id }}">
            <button type="submit" id="submitBtn" style="display: none;">Submit</button>
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if ($activeEvents && $activeEvents->count() > 0)
        const scanner = new Html5QrcodeScanner('reader', { fps: 10, qrbox: 250 });
        
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
        
        function updateEventId(select) {
            document.getElementById('event_id').value = select.value;
        }

        scanner.render((decodedText) => {
            document.getElementById('nim').value = decodedText;
            showNotification('QR Code berhasil dipindai! Memproses...', 'success');
            
            setTimeout(() => {
                document.getElementById('submitBtn').click();
            }, 1000);
        }, (errorMessage) => {
            console.error(errorMessage);
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif
            
            @if(session('error'))
                showNotification("{{ session('error') }}", 'error');
            @endif
        });
    @else
        // Tampilkan SweetAlert2 dan redirect ke dashboard
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: 'Tidak ada acara yang sedang berlangsung saat ini.',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = '{{ route("dashboard.index") }}';
                }
            });
        });
    @endif
</script>
@endsection

@section('styles')
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .notification.with-spinner::before {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 10px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
        vertical-align: middle;
    }
</style>
@endsection