<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of all admin users.
     */
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created admin user in storage.
     *
     * The password is automatically hashed via the 'hashed' cast
     * on the User model (bcrypt by default).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(User::roles())],
        ]);

        // Remove the confirmation field before mass assignment
        unset($validated['password_confirmation']);

        // Create the user — password is auto-hashed by the model's 'hashed' cast
        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Administrator account created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * If a new password is provided, it is hashed automatically.
     * If no password is provided, the existing password is retained.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(User::roles())],
        ]);

        // If a new password was entered, update it (auto-hashed by the cast).
        // If left blank, keep the existing password.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Administrator account updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * Prevent self-deletion to avoid locking yourself out.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the last admin
        if (User::where('role', User::ROLE_ADMIN)->count() <= 1 && $user->isAdmin()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete the last administrator account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Administrator account deleted successfully.');
    }
}
