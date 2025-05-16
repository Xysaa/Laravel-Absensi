<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        Log::info('User store request data:', $request->all());

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,name',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
                'is_admin' => 'required|boolean',
            ]);

            $user = User::create([
                'name' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_admin' => $validated['is_admin'],
            ]);

            Log::info('User created successfully:', ['id' => $user->id, 'name' => $user->name]);

            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Failed to create user:', ['error' => $e->getMessage()]);
            return redirect()->route('user.index')->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        Log::info('User update request data:', $request->all());

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,name,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'is_admin' => 'required|boolean',
            ]);

            $user->name = $validated['username'];
            $user->email = $validated['email'];
            $user->is_admin = $validated['is_admin'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            Log::info('User updated successfully:', ['id' => $user->id, 'name' => $user->name]);

            return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update user:', ['error' => $e->getMessage()]);
            return redirect()->route('user.index')->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $username = $user->name;
            $user->delete();
            Log::info('User deleted successfully:', ['name' => $username]);
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete user:', ['error' => $e->getMessage()]);
            return redirect()->route('user.index')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}