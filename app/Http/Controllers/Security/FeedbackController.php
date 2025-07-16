<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\DataPatrol;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function markAsDone($id, Request $request)
    {
        $data = DataPatrol::where('id', $id)
            ->where('sales_office_id', auth()->user()->sales_office_id)
            ->firstOrFail();
        
        $request->validate([
            'image'     => 'required|array|max:10',
            'image.*'   => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $imagePaths = [];

        foreach ($request->file('image') as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $imageResized = Image::make($image)->resize(1024, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('jpg', 75);

            // Simpan di folder feedback_images (perhatikan path saat `put`)
            Storage::disk('public')->put('feedback_images/' . $filename, $imageResized);

            // Simpan path
            $imagePaths[] = 'feedback_images/' . $filename;
        }

        // Update baris tersebut
        $data->status = 'done';
        $data->feedback_image = json_encode($imagePaths); // Kolom 'feedback_image' bukan 'feedback_admin'
        $data->save();

        return redirect()->route('security.feedback.show', $id)->with('success', 'Feedback telah ditandai sebagai selesai.');
    }

}
