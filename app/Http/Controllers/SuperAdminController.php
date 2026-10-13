<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_tbl;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{

public function adminAccounts()
{
    $admins = User_tbl::where('role', 'admin')
        ->orderBy('lastname')
        ->get();

    $trainers = User_tbl::where('role', 'trainer')
        ->orderBy('lastname')
        ->get();

    return view('superadmin.dashboard', compact('admins', 'trainers'));
}


    
public function trainerAccounts()
{
    $admins = User_tbl::where('role', 'admin')
        ->orderBy('lastname')
        ->get();

    $trainers = User_tbl::where('role', 'trainer')
        ->orderBy('lastname')
        ->get();

    return view('superadmin.dashboard', compact('admins', 'trainers'));
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

    
public function storeTrainer(Request $request)
{
    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname'  => 'required|string|max:255',
        'email'     => 'required|email|max:255|unique:user_tbls,email',
        'username'  => 'required|string|max:255|unique:user_tbls,username',
        'password'  => 'required|string|min:8|confirmed',
    ]);

    User_tbl::create([
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'username' => $request->username,
        'password' => $request->password,
        'role' => 'trainer',
        'status' => 'Active',
        'must_reset_password' => true,
    ]);

    return redirect()
        ->route('superadmin.dashboard')
        ->with('success', 'Trainer account created successfully.');
}



    public function updateAdmin(Request $request, $id)
    {
        // Protect the original Admin account.
        if ((int) $id === 1) {
            abort(403, 'The original Admin account cannot be edited.');
        }

        $admin = User_tbl::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_tbls', 'email')->ignore($admin->id),
            ],
            'username'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_tbls', 'username')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->email = $request->email;
        $admin->username = $request->username;

        // Only change the password when a new one is provided.
        if ($request->filled('password')) {
            $admin->password = $request->password;
            $admin->must_reset_password = true;
        }

        $admin->save();

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Administrator account updated successfully.');
    }

}