@extends('admin.layout', ['title' => 'Education', 'subtitle' => 'Manage your academic background.'])

@section('content')

    <div class="page-head">
        <div>
            <h2>Education</h2>
            <p>{{ $educations->count() }} record(s) total</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('admin.educations.create') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Education
            </a>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($educations->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    <p>No education records yet.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">Order</th>
                                <th>Degree</th>
                                <th>Institution</th>
                                <th>Years</th>
                                <th>Result</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($educations as $edu)
                                <tr>
                                    <td class="cell-muted">{{ $edu->sort_order }}</td>
                                    <td class="cell-strong">{{ $edu->degree }}</td>
                                    <td>{{ $edu->institution }}</td>
                                    <td class="cell-muted">
                                        {{ $edu->start_year }}
                                        &rarr;
                                        {{ $edu->is_current ? 'Present' : ($edu->end_year ?: '—') }}
                                    </td>
                                    <td>{{ $edu->result ?: '—' }}</td>
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('admin.educations.edit', $edu->id) }}" class="icon-btn" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.educations.destroy', $edu->id) }}"
                                                  onsubmit="return confirm('Delete this education record?');"
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

    {{ $educations->links() }}

@endsection
