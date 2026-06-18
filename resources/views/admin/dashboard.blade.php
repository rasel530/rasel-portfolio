@extends('admin.layout', ['title' => 'Dashboard', 'subtitle' => "Welcome back, here's what's happening with your portfolio."])

@section('content')

    <!-- ===================== Stat cards -->
    <div class="stat-grid">
        <div class="stat">
            <div class="stat__icon stat__icon--blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <div>
                <div class="stat__value">{{ $totalMessages }}</div>
                <div class="stat__label">Total Messages</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            <div>
                <div class="stat__value">{{ $unreadMessages }}</div>
                <div class="stat__label">Unread Messages</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <div>
                <div class="stat__value">{{ $projectsCount }}</div>
                <div class="stat__label">Projects</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--violet">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <div>
                <div class="stat__value">{{ $skillsCount }}</div>
                <div class="stat__label">Skills</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            </div>
            <div>
                <div class="stat__value">{{ $experiencesCount }}</div>
                <div class="stat__label">Experiences</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
            </div>
            <div>
                <div class="stat__value">{{ $educationsCount }}</div>
                <div class="stat__label">Educations</div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:26px;">

        <!-- ===================== Recent messages -->
        <section class="card">
            <div class="card__head">
                <div>
                    <h3>Recent Messages</h3>
                    <p>Latest 5 messages from your contact form</p>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="btn btn--outline btn--sm">
                    View all
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <div class="card__body card__body--flush">
                @if ($recentMessages->isEmpty())
                    <div class="table-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <p>No messages yet.</p>
                    </div>
                @else
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="col-actions">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentMessages as $message)
                                    <tr>
                                        <td>
                                            <span class="cell-strong">{{ $message->name }}</span><br>
                                            <span class="cell-muted">{{ $message->email }}</span>
                                        </td>
                                        <td>{{ $message->subject ?: '—' }}</td>
                                        <td class="cell-muted">{{ $message->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if ($message->is_read)
                                                <span class="badge badge--gray"><span class="badge__dot"></span>Read</span>
                                            @else
                                                <span class="badge badge--red"><span class="badge__dot"></span>Unread</span>
                                            @endif
                                        </td>
                                        <td class="col-actions">
                                            <a href="{{ route('admin.messages.show', $message->id) }}" class="icon-btn" title="View">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        <!-- ===================== Quick actions -->
        <section class="card">
            <div class="card__head">
                <h3>Quick Actions</h3>
            </div>
            <div class="card__body">
                <div class="quick-actions">
                    <a href="{{ route('admin.profiles.edit', optional(\App\Models\Profile::first())->id ?? 1) }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit Profile
                    </a>
                    <a href="{{ route('admin.skills.create') }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        Add Skill
                    </a>
                    <a href="{{ route('admin.experiences.create') }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        Add Experience
                    </a>
                    <a href="{{ route('admin.educations.create') }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        Add Education
                    </a>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn--primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Project
                    </a>
                    <a href="{{ route('admin.trainings.create') }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        Add Training
                    </a>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        View Messages
                        @php $unread = \App\Models\Message::where('is_read', false)->count(); @endphp
                        @if ($unread > 0)
                            <span class="sidebar__badge">{{ $unread }}</span>
                        @endif
                    </a>
                    @can('manage-settings')
                    <a href="{{ route('admin.settings.edit') }}" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Site Settings
                    </a>
                    @endcan
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn--outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        View Public Site
                    </a>
                </div>
            </div>
        </section>

    </div>

@endsection
