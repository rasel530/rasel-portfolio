@extends('admin.layout', ['title' => 'Add User', 'subtitle' => 'Create a new administrator account.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Add User</h2>
                <p>Fill in the details below to create a new administrator account.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Users
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="card">
                <div class="card__head"><h3>Account Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('name') has-error @enderror">
                            <label class="field__label" for="name">Name <span class="req">*</span></label>
                            <input class="input" type="text" id="name" name="name"
                                   value="{{ old('name') }}" placeholder="e.g. Rasel Bepari" required>
                            @error('name') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('email') has-error @enderror">
                            <label class="field__label" for="email">Email <span class="req">*</span></label>
                            <input class="input" type="email" id="email" name="email"
                                   value="{{ old('email') }}" placeholder="e.g. rasel@example.com" required>
                            @error('email') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('password') has-error @enderror">
                            <label class="field__label" for="password">Password <span class="req">*</span></label>
                            <input class="input" type="password" id="password" name="password"
                                   autocomplete="new-password" required>
                            <span class="field__hint">Minimum 8 characters. Will be securely hashed with bcrypt.</span>
                            @error('password') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('password_confirmation') has-error @enderror">
                            <label class="field__label" for="password_confirmation">Confirm Password <span class="req">*</span></label>
                            <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                                   autocomplete="new-password" required>
                            @error('password_confirmation') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('role') has-error @enderror">
                            <label class="field__label" for="role">Role <span class="req">*</span></label>
                            <select class="select" id="role" name="role" required>
                                <option value="" disabled selected>Select a role</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                            </select>
                            @error('role') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create User
                </button>
            </div>
        </form>

    </div>

@endsection
