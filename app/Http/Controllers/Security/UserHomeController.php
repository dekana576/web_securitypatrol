<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\DataPatrol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserHomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $salesOfficeId = $user->sales_office_id;

        $now = Carbon::now();
        $today = $now->toDateString();
        $currentHour = (int)$now->format('H');
        $currentMinute = (int)$now->format('i');

        $shift = 'pagi';
        $target = 8;

        if ($currentHour >= 6 && ($currentHour < 14 || ($currentHour == 14 && $currentMinute == 0))) {
            $shift = 'pagi';
        } elseif ($currentHour >= 14 && $currentHour < 22) {
            $shift = 'siang';
        } else {
            $shift = 'malam';
        }

        $startTime = match($shift) {
            'pagi' => '06:01:00',
            'siang' => '14:01:00',
            'malam' => '22:01:00',
        };

        $endTime = match($shift) {
            'pagi' => '14:00:00',
            'siang' => '22:00:00',
            'malam' => '23:59:59',
        };

        if ($shift == 'malam' && $currentHour < 6) {
            $start = Carbon::parse($today)->subDay()->setTimeFromTimeString('22:01:00');
            $end = Carbon::parse($today)->setTimeFromTimeString('06:00:00');
        } else {
            $start = Carbon::parse($today . ' ' . $startTime);
            $end = Carbon::parse($today . ' ' . $endTime);
        }

        $dataPatrolCount = DataPatrol::where('sales_office_id', $salesOfficeId)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $remaining = max(0, $target - $dataPatrolCount);

        $unreadFeedbackCount = DataPatrol::where('sales_office_id', $salesOfficeId)
            ->whereNotNull('feedback_admin')
            ->where('status', 'submitted')
            ->count();


        return view('user.user_home', compact('user', 'shift', 'remaining', 'unreadFeedbackCount'));
    }

    public function changePassword()
    {
        return view('user.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::find(auth()->id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }

}
