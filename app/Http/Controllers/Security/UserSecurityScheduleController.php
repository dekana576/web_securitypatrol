<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\SecuritySchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSecurityScheduleController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $start = $now->copy()->startOfMonth();
        $end = $now->copy()->endOfMonth();

        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates->push($date->copy());
        }

        $jadwals = SecuritySchedule::whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->where(function ($query) use ($user) {
                $query->where('security_1_id', $user->id)
                    ->orWhere('security_2_id', $user->id);
            })
            ->get()
            ->groupBy('tanggal');

        $schedule = [];

        foreach ($dates as $date) {
            $tanggal = $date->format('Y-m-d');
            $hari = $date->translatedFormat('l');

            if ($jadwals->has($tanggal)) {
                foreach ($jadwals[$tanggal] as $jadwal) {
                    $schedule[] = [
                        'tanggal' => $tanggal,
                        'hari' => $hari,
                        'shift' => $jadwal->shift,
                        'jam_mulai' => $jadwal->jam_mulai,
                        'jam_selesai' => $jadwal->jam_selesai,
                        'off' => false
                    ];
                }
            } else {
                $schedule[] = [
                    'tanggal' => $tanggal,
                    'hari' => $hari,
                    'shift' => 'Day Off',
                    'jam_mulai' => '-',
                    'jam_selesai' => '-',
                    'off' => true
                ];
            }
        }

        return view('user.user_jadwal', compact('schedule'));
    }

}
