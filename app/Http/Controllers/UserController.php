<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::latest();

            // Filter by active/archived status
            if ($request->input('archived') === 'true') {
                $query->onlyTrashed();
            }

            $users = $query->paginate(10)->onEachSide(1);
            return view('user.index', compact('users'));
        } catch (Exception $e) {
            return redirect()->route('user.index')->with('error', 'An error occurred while fetching users.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('user.edit', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('user.index')->with('error', 'User not found or an error occurred.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'name' => $request->input('name'),
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => bcrypt($request->input('password')),
                ]);
            }

            return redirect()->route('user.index', ['user' => $user->id])
                ->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('user.index')->with('error', 'An error occurred while updating the user.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user.index')->with('success', 'User archived successfully.');
        } catch (Exception $e) {
            return redirect()->route('user.index')->with('error', 'An error occurred while archiving the user.');
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->route('user.index', ['archived' => 'true'])->with('success', 'User restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('user.index')->with('error', 'An error occurred while restoring the user.');
        }
    }
}
