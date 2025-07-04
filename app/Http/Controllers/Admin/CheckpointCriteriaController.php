<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\CheckpointCriteria;
use App\Models\Region;
use App\Models\SalesOffice;
use Illuminate\Http\Request;

class CheckpointCriteriaController extends Controller
{
    public function index($checkpointId)
    {
        $checkpoint = Checkpoint::with(['region', 'salesOffice'])->findOrFail($checkpointId);
        $criterias = CheckpointCriteria::where('checkpoint_id', $checkpointId)->get();

        return view('admin.checkpoint.kriteria_checkpoint', compact('checkpoint', 'criterias'));
    }

    public function store(Request $request, $checkpointId)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'positive_answer' => 'required|string|max:255',
            'negative_answer' => 'required|string|max:255',
        ]);

        $checkpoint = Checkpoint::with(['region', 'salesOffice'])->findOrFail($checkpointId);

        CheckpointCriteria::create([
            'checkpoint_id'     => $checkpoint->id,
            'region_id'         => $checkpoint->region_id,
            'sales_office_id'   => $checkpoint->sales_office_id,
            'nama_kriteria'     => $request->nama_kriteria,
            'positive_answer'   => $request->positive_answer,
            'negative_answer'   => $request->negative_answer,
        ]);

        return redirect()->route('checkpoint_criteria.index', $checkpointId)
                         ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $kriteria = CheckpointCriteria::findOrFail($id);
        $checkpointId = $kriteria->checkpoint_id;
        $kriteria->delete();

        return redirect()->route('checkpoint_criteria.index', $checkpointId)
                         ->with('success', 'Kriteria berhasil dihapus.');
    }
}
