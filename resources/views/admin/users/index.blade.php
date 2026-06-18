@extends('admin.layout', ['title' => 'Users', 'subtitle' => 'Manage administrator accounts.'])

@section('content')

    <div class="page-head">
        <div>
            <h2>Users</h2>
            <p>{{ $users->total() }} administrator(s) total</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add User
            </a>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($users->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <p>No users yet. Click &ldquo;Add User&rdquo; to create an administrator account.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="cell-strong">
                                        {{ $user->name }}
                                        @if ($user->id === auth()->id())
                                            <span class="badge--you">You</span>
                                        @endif
                                    </td>
                                    <td class="cell-muted">{{ $user->email }}</td>
                                    <td>
                                        @switch($user->role)
                                            @case('admin') <span class="badge badge--indigo">Admin</span> @break
                                            @case('editor') <span class="badge badge--blue">Editor</span> @break
                                            @default <span class="badge badge--gray">{{ ucfirst($user->role) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="cell-muted">{{ $user->created_at->format('M j, Y') }}</td>
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="icon-btn" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            @if ($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                      onsubmit="return confirm('Delete user &ldquo;{{ e($user->name) }}&rdquo;? This cannot be undone.');"
                                                      style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="icon-btn icon-btn--danger" title="Delete">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    {{ $users->links() }}

@endsection
