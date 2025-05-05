<div id="reader" width="600px"></div>
<div id="notification" class="notification" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #4CAF50; color: white; padding: 20px; border-radius: 5px; z-index: 9999; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"></div>

<form method="POST" action="{{ route('kehadiran.record') }}">
  @csrf
  <input type="hidden" name="nim" id="nim">
  <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
  <button type="submit" id="submitBtn" style="display: none;">Submit</button>
</form>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    const scanner = new Html5QrcodeScanner('reader', { fps: 10, qrbox: 250 });
    
    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
        notification.style.display = 'block';
        
        // Hide notification after form submission (optional)
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }
    
    scanner.render((decodedText) => {
        document.getElementById('nim').value = decodedText;
        showNotification('QR Code berhasil dipindai! Memproses...', 'success');
        
        // Delay form submission slightly to show the notification
        setTimeout(() => {
            document.getElementById('submitBtn').click();
        }, 1000);
    }, (errorMessage) => {
        // handle errors
        console.error(errorMessage);
    });
    
    // Also show flash messages from controller if available
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showNotification("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showNotification("{{ session('error') }}", 'error');
        @endif
    });
</script>

<style>
    /* Optional: Add a loading spinner to the notification */
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