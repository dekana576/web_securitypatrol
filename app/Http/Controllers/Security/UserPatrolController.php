<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\CheckpointCriteria;
use Illuminate\Http\Request;

class UserPatrolController extends Controller
{
    public function showScanForm($checkpointCode)
    {
        $checkpoint = Checkpoint::where('checkpoint_code', $checkpointCode)->firstOrFail();
        $criterias = CheckpointCriteria::where('checkpoint_id', $checkpoint->id)->get();

        return view('user.user_scan_qr', compact('checkpoint', 'criterias'));
    }
}
