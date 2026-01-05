<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * ============================
     * INDEX — ADMIN ONLY (policy)
     * ============================
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * ============================
     * CREATE — ADMIN ONLY (policy)
     * ============================
     */
    public function create()
    {
        $this->authorize('viewAny', User::class);

        return view('admin.users.create');
    }

    /**
     * STORE — ADMIN ONLY (policy)
     */
    public function store(Request $request)
    {
        $this->authorize('viewAny', User::class);

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

            // default role
            $user->assignRole('staff');
        });

        return redirect()->route('admin.users.index');
    }

    /**
     * ============================
     * SHOW — ADMIN ANY | STAFF SELF (policy)
     * ============================
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load([
            'roles:id,name',
            'screens:id,name,venue_id',
            'screens.venue:id,name',
        ]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * ============================
     * EDIT — ADMIN ONLY (policy)
     * ============================
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * UPDATE — ADMIN ONLY (policy)
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

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

        return redirect()->route('admin.users.index');
    }

    /**
     * ============================
     * DELETE — ADMIN ONLY (policy)
     * ============================
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'You cannot deactivate your own account.',
            ]);
        }

        // prevent last admin lockout
        if (
            $user->hasRole('admin') &&
            User::role('admin')->where('active', true)->count() <= 1
        ) {
            return back()->withErrors([
                'user' => 'You cannot deactivate the last active admin.',
            ]);
        }

        $user->update(['active' => false]);

        return redirect()->route('admin.users.index');
    }

    /**
     * ============================
     * TOGGLE STATUS — ADMIN ONLY (policy)
     * ============================
     */
    public function toggleStatus(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $validated = $request->validate([
            'id'     => 'required|exists:users,id',
            'active' => 'required|boolean',
        ]);

        $user = User::lockForUpdate()->findOrFail($validated['id']);

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

        return response()->json(['success' => true]);
    }
}
