<?php
namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkpoint;

class ScanQRController extends Controller
{
    // Tampilkan form scan QR
    public function form()
    {
        return view('user.user_scan_qr');
    }

    // Proses hasil scan QR
    public function result(Request $request)
    {
        $code = $request->code;

        $checkpoint = Checkpoint::where('checkpoint_code', $code)->first();

        if (!$checkpoint) {
            return redirect()->route('user.scan.qr')->with('error', 'Checkpoint tidak ditemukan.');
        }

        return redirect()->route('patrol.create', ['checkpoint' => $checkpoint->id]);
    }
}
