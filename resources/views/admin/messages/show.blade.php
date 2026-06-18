@extends('admin.layout', ['title' => 'View Message', 'subtitle' => 'Contact form submission.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>{{ $message->subject ?: '(No subject)' }}</h2>
                <p>From {{ $message->name }}</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.messages.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Messages
                </a>
            </div>
        </div>

        <section class="card">
            <div class="card__head">
                <div>
                    <h3>Message Details</h3>
                    <p>Received {{ $message->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                @if ($message->is_read)
                    <span class="badge badge--gray">Read</span>
                @else
                    <span class="badge badge--indigo"><span class="badge__dot"></span>Unread</span>
                @endif
            </div>
            <div class="card__body">
                <dl class="detail-list">
                    <div>
                        <dt>From</dt>
                        <dd>{{ $message->name }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
                    </div>
                    @if ($message->phone)
                        <div>
                            <dt>Phone</dt>
                            <dd><a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></dd>
                        </div>
                    @endif
                    <div>
                        <dt>Subject</dt>
                        <dd>{{ $message->subject ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt>Received</dt>
                        <dd>{{ $message->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    </div>
                </dl>

                <h3 style="font-size:13px; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-faint); margin:22px 0 10px;">Message</h3>
                <div class="message-body">{{ $message->message }}</div>
            </div>
        </section>

        <div class="form-actions">
            <form method="POST" action="{{ route('admin.messages.read', $message->id) }}" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    {{ $message->is_read ? 'Mark Unread' : 'Mark Read' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}"
                  onsubmit="return confirm('Delete this message from {{ e($message->name) }}?');"
                  style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn--danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Delete
                </button>
            </form>
        </div>

    </div>

@endsection
