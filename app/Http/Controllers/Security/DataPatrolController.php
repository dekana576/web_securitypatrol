<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{DataPatrol, Checkpoint, CheckpointCriteria};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DataPatrolController extends Controller
{
    // Tampilkan form setelah QR scan
    public function create($checkpointCode)
    {
        $checkpoint = Checkpoint::where('checkpoint_code', $checkpointCode)
                        ->with(['region', 'salesOffice'])
                        ->firstOrFail();

        $criterias = CheckpointCriteria::where('checkpoint_id', $checkpoint->id)->get();
        $user = Auth::user();

        return view('user.user_scan_qr', compact('checkpoint', 'criterias', 'user'));
    }

    // Simpan data patrol
    public function store(Request $request)
    {
        $request->validate([
            'checkpoint_id'     => 'required|exists:checkpoints,id',
            'region_id'         => 'required|exists:regions,id',
            'sales_office_id'   => 'required|exists:sales_offices,id',
            'description'       => 'required|string',
            'criteria'          => ['required', 'array'],
            'criteria.*'        => ['required'],
            'image'             => 'required|array|max:10',
            'image.*'           => 'image|mimes:jpg,jpeg,png|max:5120',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
        ]);

        $user = Auth::user();
        $imagePaths = [];

        // Simpan semua gambar
        foreach ($request->file('image') as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $imageResized = Image::make($image)->resize(1024, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('jpg', 75);

            Storage::disk('public')->put('patrol_images/' . $filename, $imageResized);
            $imagePaths[] = 'patrol_images/' . $filename;
        }

        $negativeAnswers = collect($request->criteria)->filter(fn($val) =>
            str_contains(strtolower($val), 'tidak') || str_contains(strtolower($val), 'negative')
        );

        DataPatrol::create([
            'tanggal'           => Carbon::now(),
            'region_id'         => $request->region_id,
            'sales_office_id'   => $request->sales_office_id,
            'checkpoint_id'     => $request->checkpoint_id,
            'user_id'           => $user->id,
            'description'       => $request->description,
            'kriteria_result'   => json_encode($request->criteria),
            'status'            => 'submitted',
            'image'             => json_encode($imagePaths), // Simpan array path sebagai JSON
            'lokasi'            => $request->latitude . ',' . $request->longitude,
            'feedback_admin'    => null,
        ]);

        return redirect()->route('user.home')->with('success', 'Data patroli berhasil dikirim.');
    }
}
