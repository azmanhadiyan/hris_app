<?php

namespace App\Http\Controllers\ManajemenUser;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'super_admin') {
                abort(403, 'Akses ditolak.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::all();
        return view('manajemen_users.users.index', compact('users'));
    }

    public function create()
    {
        return view('manajemen_users.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'    => 'required|string|max:255|unique:users,username',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:6',
            'departement' => 'required|string|max:255',
            'role'        => 'required|string|max:255',
        ]);

        User::create([
            'username'    => $request->username,
            'name'        => $request->name,
            'email'       => $request->email,
            'departement' => $request->departement,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
        ]);

        return redirect()->route('manajemen_users.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('manajemen_users.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username'    => 'required|string|max:255|unique:users,username,' . $user->id,
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'departement' => 'required|string|max:255',
            'role'        => 'required|string|max:255',
            'password'    => 'nullable|min:6',
        ]);

        $data = $request->only(['username', 'name', 'email', 'departement', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('manajemen_users.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('manajemen_users.users.index')->with('success', 'User berhasil dihapus.');
    }
}
