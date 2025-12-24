<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'active'   => true,
            ]);

            // Default role
            $user->assignRole('staff');
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load([
            'roles:id,name',
            'screens:id,name,venue_id',
            'screens.venue:id,name,address',
        ]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
        ]);

        DB::transaction(function () use ($validated, $user) {

            $data = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Deactivate user (soft disable).
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            abort(403, 'You cannot deactivate your own account.');
        }

        // SAFETY: prevent locking out the last admin
        if ($user->hasRole('admin') && User::role('admin')->where('active', true)->count() <= 1) {
            throw ValidationException::withMessages([
                'user' => 'You cannot deactivate the last active admin.',
            ]);
        }

        $user->update(['active' => false]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deactivated successfully.');
    }

    /**
     * Toggle active/inactive status (AJAX).
     */
    public function toggleStatus(Request $request)
    {
        $validated = $request->validate([
            'id'     => 'required|exists:users,id',
            'active' => 'required|boolean',
        ]);

        if ((int) $validated['id'] === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own status.',
            ], 403);
        }

        return DB::transaction(function () use ($validated) {

            $user = User::lockForUpdate()->findOrFail($validated['id']);

            // Prevent last admin lockout
            if (
                $user->hasRole('admin') &&
                ! $validated['active'] &&
                User::role('admin')->where('active', true)->count() <= 1
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot deactivate the last active admin.',
                ], 422);
            }

            $user->update(['active' => $validated['active']]);

            return response()->json([
                'success' => true,
                'message' => $validated['active']
                    ? 'User activated successfully.'
                    : 'User deactivated successfully.',
            ]);
        });
    }
    public function profile()
    {
        $user = auth()->user();

        $this->authorize('view', $user);

        return redirect()->route('admin.users.show', $user);
    }
}
