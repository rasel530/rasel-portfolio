@extends('admin.layout', ['title' => 'Edit User', 'subtitle' => 'Update this administrator account.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit User</h2>
                <p>Updating &ldquo;{{ $user->name }}&rdquo;</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Users
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card__head"><h3>Account Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('name') has-error @enderror">
                            <label class="field__label" for="name">Name <span class="req">*</span></label>
                            <input class="input" type="text" id="name" name="name"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('email') has-error @enderror">
                            <label class="field__label" for="email">Email <span class="req">*</span></label>
                            <input class="input" type="email" id="email" name="email"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('password') has-error @enderror">
                            <label class="field__label" for="password">New Password</label>
                            <input class="input" type="password" id="password" name="password"
                                   autocomplete="new-password">
                            <span class="field__hint">Leave blank to keep current password. Enter a new password to change it.</span>
                            @error('password') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('password_confirmation') has-error @enderror">
                            <label class="field__label" for="password_confirmation">Confirm New Password</label>
                            <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                                   autocomplete="new-password">
                            @error('password_confirmation') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('role') has-error @enderror">
                            <label class="field__label" for="role">Role <span class="req">*</span></label>
                            <select class="select" id="role" name="role" required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                            </select>
                            @error('role') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Changes
                </button>
            </div>
        </form>

    </div>

@endsection
