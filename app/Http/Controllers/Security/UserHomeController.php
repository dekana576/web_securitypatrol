<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserHomeController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Pastikan user punya relasi dengan sales office
        $salesOffice = $user->salesOffice;

        return view('user.user_home', compact('user', 'salesOffice'));
    }
}
