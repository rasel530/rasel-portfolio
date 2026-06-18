@extends('admin.layout', ['title' => 'Messages', 'subtitle' => 'Inbox of contact form submissions.'])

@php
    $unreadCount = \App\Models\Message::where('is_read', false)->count();
@endphp

@section('content')

    <div class="page-head">
        <div>
            <h2>Messages</h2>
            <p>{{ $unreadCount }} unread of {{ $messages->total() }} total</p>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($messages->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <p>No messages yet. Submissions from your contact form will appear here.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Received</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $message)
                                <tr class="@unless($message->is_read) row--unread @endunless">
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message->id) }}" class="cell-strong">{{ $message->name }}</a>
                                        <div class="cell-muted" style="font-size:12.5px; margin-top:2px;">{{ $message->email }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message->id) }}">
                                            {{ $message->subject ?: '(No subject)' }}
                                        </a>
                                    </td>
                                    <td class="cell-muted">
                                        {{ $message->created_at->format('M d, Y') }}
                                        <div style="font-size:12px;">{{ $message->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td>
                                        @if ($message->is_read)
                                            <span class="badge badge--gray">Read</span>
                                        @else
                                            <span class="badge badge--indigo"><span class="badge__dot"></span>Unread</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    @if ($messages->hasPages())
        <nav class="pagination">
            @if ($messages->onFirstPage())
                <span class="disabled">Prev</span>
            @else
                <a href="{{ $messages->previousPageUrl() }}">Prev</a>
            @endif

            <span>Page {{ $messages->currentPage() }} of {{ $messages->lastPage() }}</span>

            @if ($messages->hasMorePages())
                <a href="{{ $messages->nextPageUrl() }}">Next</a>
            @else
                <span class="disabled">Next</span>
            @endif
        </nav>
    @endif

@endsection
