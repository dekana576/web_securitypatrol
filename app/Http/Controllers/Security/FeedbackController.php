<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\DataPatrol;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();

        $feedbacks = DataPatrol::where('sales_office_id', $user->sales_office_id)
            ->whereNotNull('feedback_admin')
            ->latest()
            ->get();



        return view('user.feedback.feedback', compact('feedbacks'));
    }

    public function show($id)
    {
        $feedback = DataPatrol::with(['region', 'salesOffice', 'checkpoint', 'user'])
            ->where('id', $id)
            ->where('sales_office_id', auth()->user()->sales_office_id)
            ->firstOrFail();

        return view('user.feedback.view_feedback', compact('feedback'));
    }

    public function markAsDone($id)
    {
        $data = DataPatrol::where('id', $id)
            ->where('sales_office_id', auth()->user()->sales_office_id)
            ->firstOrFail();

        $data->status = 'done';
        $data->save();

        return redirect()->route('security.feedback.show', $id)->with('success', 'Feedback telah ditandai sebagai selesai.');
    }

}
