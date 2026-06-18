@extends('admin.layout', ['title' => 'Projects', 'subtitle' => 'Manage your project showcase.'])

@section('content')

    <div class="page-head">
        <div>
            <h2>Projects</h2>
            <p>{{ $projects->count() }} project(s) total</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('admin.projects.create') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Project
            </a>
        </div>
    </div>

    <section class="card">
        <div class="card__body card__body--flush">
            @if ($projects->isEmpty())
                <div class="table-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    <p>No projects yet. Click &ldquo;Add Project&rdquo; to showcase your first one.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Featured</th>
                                <th>Links</th>
                                <th style="width:70px;">Sort</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>
                                        <div class="cell-strong">{{ $project->title }}</div>
                                        @if ($project->technologies)
                                            <div class="cell-muted" style="font-size:12.5px; margin-top:3px;">{{ $project->technologies }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($project->is_featured)
                                            <span class="badge badge--amber"><span class="badge__dot"></span>Featured</span>
                                        @else
                                            <span class="cell-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row-actions">
                                            @if ($project->demo_url)
                                                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="icon-btn" title="Live demo">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                                </a>
                                            @endif
                                            @if ($project->source_url)
                                                <a href="{{ $project->source_url }}" target="_blank" rel="noopener noreferrer" class="icon-btn" title="Source code">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                                                </a>
                                            @endif
                                            @if (! $project->demo_url && ! $project->source_url)
                                                <span class="cell-muted">&mdash;</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="cell-muted">{{ $project->sort_order }}</td>
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('admin.projects.edit', $project->id) }}" class="icon-btn" title="Edit">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}"
                                                  onsubmit="return confirm('Delete project &ldquo;{{ e($project->title) }}&rdquo;?');"
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

    {{ $projects->links() }}

@endsection
