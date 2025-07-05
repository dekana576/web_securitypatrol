<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\SalesOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('salesOffice')->get();
        return view('admin.user.user', compact('users'));
    }

    public function create()
    {
        $salesOffices = SalesOffice::all();
        $regions = Region::all();
        return view('admin.user.add_user', compact('salesOffices','regions'));
    }

    public function getdata(Request $request)
    {
        $users = User::with(['salesOffice','region']);

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('user.edit', $row->id) . '" class="action-icon edit-icon"><i class="fa-solid fa-file-pen" title="Edit"></i></a>
                    <a href="" class="action-icon delete-icon delete" data-id="' . $row->id . '"><i class="fa-solid fa-trash" title="Delete"></i></a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'nik' => 'required|string|max:255|unique:users,nik',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,security',
            'sales_office_id' => 'required|exists:sales_offices,id',
            'region_id' => 'required|exists:regions,id',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'nik' => $request->nik,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'sales_office_id' => $request->sales_office_id,
            'region_id' => $request->region_id,
            'email' => $request->email,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $salesOffices = SalesOffice::all();
        $regions = Region::all();
    
        return view('admin.user.edit_user', compact('user', 'salesOffices','regions'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'nik' => 'required|numeric',
            'phone_number' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'role' => 'required|in:admin,security',
            'sales_office_id' => 'required|exists:sales_offices,id',
            'region_id' => 'required|exists:regions,id',
            'password' => 'nullable|string|min:8',
        ]);
    
        $user = User::findOrFail($id);
    
        $user->name = $request->name;
        $user->username = $request->username;
        $user->nik = $request->nik;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;
        $user->role = $request->role;
        $user->sales_office_id = $request->sales_office_id;
        $user->region_id = $request->region_id;
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }
    
        $user->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }


    
}
