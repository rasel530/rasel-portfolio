@extends('admin.layout', ['title' => 'Skills', 'subtitle' => 'Manage the skills displayed on your portfolio.'])

@section('content')

    <div class="page-head">
        <div>
            <h2>Skills</h2>
            <p>{{ $skills->count() }} skill(s) total</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('admin.skills.create') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Skill
            </a>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($skills->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <p>No skills yet. Click "Add Skill" to create your first one.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">Order</th>
                                <th>Skill</th>
                                <th>Category</th>
                                <th>Proficiency</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skills as $skill)
                                <tr>
                                    <td class="cell-muted">{{ $skill->sort_order }}</td>
                                    <td class="cell-strong">{{ $skill->name }}</td>
                                    <td>
                                        @switch($skill->category)
                                            @case('Technical') <span class="badge badge--indigo">{{ $skill->category }}</span> @break
                                            @case('Professional') <span class="badge badge--blue">{{ $skill->category }}</span> @break
                                            @case('Language') <span class="badge badge--green">{{ $skill->category }}</span> @break
                                            @case('Tool') <span class="badge badge--amber">{{ $skill->category }}</span> @break
                                            @default <span class="badge badge--gray">{{ $skill->category }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="prof">
                                            <div class="prof__track">
                                                <div class="prof__fill" style="width: {{ $skill->proficiency }}%;"></div>
                                            </div>
                                            <span class="prof__val">{{ $skill->proficiency }}%</span>
                                        </div>
                                    </td>
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('admin.skills.edit', $skill->id) }}" class="icon-btn" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.skills.destroy', $skill->id) }}"
                                                  onsubmit="return confirm('Delete skill &ldquo;{{ e($skill->name) }}&rdquo;?');"
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

    {{ $skills->links() }}

@endsection
