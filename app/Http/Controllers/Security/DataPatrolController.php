<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{DataPatrol, Checkpoint, KriteriaCheckpoint, User, Region, SalesOffice};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataPatrolController extends Controller
{
    // Tampilkan form setelah QR scan
    public function create($checkpoint_id)
    {
        $checkpoint = Checkpoint::with('region', 'salesOffice')->findOrFail($checkpoint_id);
        $kriteriaList = $checkpoint->kriteria_checkpoints;

        return view('security.patrol.create', compact('checkpoint', 'kriteriaList'));
    }

    // Simpan data patrol
    public function store(Request $request)
    {
        $request->validate([
            'checkpoint_id' => 'required|exists:checkpoints,id',
            'description' => 'required|string',
            'answers' => 'required|array',
            'answers.*' => 'in:positive,negative',
            'image' => 'required|image|max:2048',
            'location' => 'required|string',
        ]);

        $checkpoint = Checkpoint::with('region', 'salesOffice')->findOrFail($request->checkpoint_id);
        $security = Auth::user();

        // Simpan gambar
        $imagePath = $request->file('image')->store('patrol_images', 'public');

        // Buat ringkasan jawaban
        $negatives = array_filter($request->answers, fn($val) => $val === 'negative');

        $dataPatrol = DataPatrol::create([
            'tanggal' => now(),
            'region_id' => $checkpoint->region_id,
            'sales_office_id' => $checkpoint->sales_office_id,
            'checkpoint_id' => $checkpoint->id,
            'security_id' => $security->id,
            'description' => $request->description,
            'kriteria_result' => json_encode($request->answers),
            'status' => 'submitted',
            'image' => $imagePath,
            'location' => $request->location,
            'feedback_admin' => null,
        ]);

        return redirect()->route('security.dashboard')->with('success', 'Data patroli berhasil dikirim.');
    }
}
