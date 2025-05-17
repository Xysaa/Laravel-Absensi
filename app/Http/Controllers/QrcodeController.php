<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrCode as QrcodeModel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Anggota;

class QrcodeController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function generateQr(Request $request)
    {
        $request->validate([
            'nim' => 'required'
        ]);
    
        $anggota = Anggota::with('qrcode')->where('nim', $request->nim)->first();
    
        if (!$anggota || !$anggota->qrcode) {
            return back()->with('error', 'Data tidak ditemukan atau belum memiliki QR Code.');
        }
    
        $qrCode = QrCode::size(200)->generate($anggota->qrcode->qrcode);
    
        return view('generateqr', [
            'qrCode' => $qrCode,
            'anggota' => $anggota
        ]);
    }
    
}
