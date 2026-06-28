@extends('admin.layout', ['title' => 'Experience', 'subtitle' => 'Manage your work experience timeline.'])

@section('content')

    <div class="page-head">
        <div>
            <h2>Experience</h2>
            <p>{{ $experiences->total() }} position(s) total</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('admin.experiences.create') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Experience
            </a>
        </div>
    </div>

    {{-- ====== Total Experience Summary ====== --}}
    <div class="stats-overview" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
        <div style="background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:14px;padding:24px;color:#fff;display:flex;align-items:center;gap:18px;">
            <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div style="font-size:32px;font-weight:800;line-height:1;">{{ $totalExp['formatted'] }}</div>
                <div style="font-size:13px;opacity:0.85;margin-top:4px;">Total Professional Experience</div>
            </div>
        </div>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;align-items:center;gap:18px;">
            <div style="width:56px;height:56px;border-radius:14px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <div style="font-size:32px;font-weight:800;line-height:1;color:#0f172a;">{{ $experiences->total() }}</div>
                <div style="font-size:13px;color:#64748b;margin-top:4px;">Positions Held</div>
            </div>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($experiences->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    <p>No experience added yet.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">Order</th>
                                <th>Position</th>
                                <th>Company</th>
                                <th>Period</th>
                                <th>Status</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($experiences as $exp)
                                <tr>
                                    <td class="cell-muted">{{ $exp->sort_order }}</td>
                                    <td class="cell-strong">{{ $exp->position }}</td>
                                    <td>
                                        {{ $exp->company }}
                                        @if($exp->company_address)
                                        <br><small style="opacity:0.65;font-size:0.85em;">{{ $exp->company_address }}</small>
                                        @endif
                                    </td>
                                    <td class="cell-muted">
                                        {{ $exp->start_date ? $exp->start_date->format('M Y') : '—' }}
                                        &rarr;
                                        {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '—') }}
                                    </td>
                                    <td>
                                        @if ($exp->is_current)
                                            <span class="badge badge--green"><span class="badge__dot"></span>Current</span>
                                        @else
                                            <span class="badge badge--gray">Past</span>
                                        @endif
                                    </td>
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('admin.experiences.edit', $exp->id) }}" class="icon-btn" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.experiences.destroy', $exp->id) }}"
                                                  onsubmit="return confirm('Delete this experience entry?');"
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

    {{ $experiences->links() }}

@endsection
