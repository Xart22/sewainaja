<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required|unique:users',
                'name' => 'required',
                'role' => 'required',
                'password' => 'required',
                'phone_number' => 'required',
            ]);
            User::create($request->except('_token'));

            return redirect()->route('manage-user.index')->with('success', 'User created successfully');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('users.show', [
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('users.edit', [
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'nip' => 'required|unique:users,nip,' . $id,
                'name' => 'required',
                'role' => 'required',
                'phone_number' => 'required',
            ]);
            if ($request->password) {
                $request->validate([
                    'password' => 'required',
                ]);
            }

            $user = User::findOrFail($id);
            if ($request->password) {
                $user->update($request->except('_token'));
            } else {
                $user->update($request->except('_token', 'password'));
            }


            return redirect()->route('manage-user.index')->with('success', 'User updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::findOrFail($id)->delete();
            return redirect()->route('manage-user.index')->with('success', 'User deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
