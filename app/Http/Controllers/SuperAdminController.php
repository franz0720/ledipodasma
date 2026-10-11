<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_tbl;

class SuperAdminController extends Controller
{
    public function adminAccounts()
    {
        $admins = User_tbl::where('role', 'admin')
            ->orderBy('lastname')
            ->get();

        return view('superadmin.dashboard', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:user_tbls,email',
            'username'  => 'required|string|max:255|unique:user_tbls,username',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        User_tbl::create([
            'firstname'           => $request->firstname,
            'lastname'            => $request->lastname,
            'email'               => $request->email,
            'username'            => $request->username,
            'password'            => bcrypt($request->password),
            'role'                => 'admin',
            'status'              => 'Active',
            'must_reset_password' => true,
        ]);

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Administrator account created successfully.');
    }
}