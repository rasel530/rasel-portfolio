@extends('admin.layout', ['title' => 'Training', 'subtitle' => 'Manage your training and certifications.'])

@section('content')

    <div class="page-head">
        <div>
            <h2>Training &amp; Certifications</h2>
            <p>{{ $trainings->total() }} entry/entries total</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('admin.trainings.create') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Training
            </a>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($trainings->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    <p>No training added yet.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">Order</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Period</th>
                                <th>Status</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainings as $training)
                                <tr>
                                    <td class="cell-muted">{{ $training->sort_order }}</td>
                                    <td class="cell-strong">
                                        {{ $training->title }}
                                        <a href="{{ $training->publicUrl() }}" target="_blank" rel="noopener" style="margin-left:6px;color:#6366f1;" title="View public page">
                                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </a>
                                    </td>
                                    <td>{{ $training->organization ?? '—' }}</td>
                                    <td class="cell-muted">{{ $training->yearRange() ?: '—' }}</td>
                                    <td>
                                        @if ($training->is_featured)
                                            <span class="badge badge--green"><span class="badge__dot"></span>Featured</span>
                                        @else
                                            <span class="badge badge--gray">Standard</span>
                                        @endif
                                    </td>
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('admin.trainings.edit', $training->id) }}" class="icon-btn" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.trainings.destroy', $training->id) }}"
                                                  onsubmit="return confirm('Delete this training entry?');"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="icon-btn icon-btn--danger" title="Delete">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
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

    {{ $trainings->links() }}

@endsection
