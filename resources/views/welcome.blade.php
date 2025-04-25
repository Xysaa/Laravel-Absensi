<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Generate QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-4">QR Code Generator</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('generate.qr') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="nim" placeholder="Masukkan NIM" required
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Generate QR</button>
        </form>

        @isset($qrCode)
            <div class="mt-6 text-center">
                <p class="mb-2 text-gray-600">QR Code untuk: <strong>{{ $anggota->nama }}</strong> ({{ $anggota->nim }})</p>
                <div class="inline-block p-4 bg-white border rounded shadow">
                    {!! $qrCode !!}
                </div>
            </div>
        @endisset
    </div>
</body>
</html>
