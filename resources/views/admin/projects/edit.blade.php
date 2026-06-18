@extends('admin.layout', ['title' => 'Edit Project', 'subtitle' => 'Update this project entry.'])

@php
    $imageExists = $project->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($project->image);
@endphp

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit Project</h2>
                <p>Updating &ldquo;{{ $project->title }}&rdquo;</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.projects.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Projects
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card__head"><h3>Project Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('title') has-error @enderror">
                            <label class="field__label" for="title">Title <span class="req">*</span></label>
                            <input class="input" type="text" id="title" name="title"
                                   value="{{ old('title', $project->title) }}" required>
                            @error('title') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Description <span class="req">*</span></label>
                            <textarea class="textarea" id="description" name="description" rows="5" required>{{ old('description', $project->description) }}</textarea>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('image') has-error @enderror">
                            <label class="field__label">Project Image</label>
                            <div class="file-upload">
                                <div class="file-preview">
                                    @if ($imageExists)
                                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ e($project->title) }}">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    @endif
                                </div>
                                <div class="file-input-wrap">
                                    <label class="file-drop" for="image">
                                        <strong>Click to replace the image</strong>
                                        @if ($project->image)
                                            Current: <span class="text-muted">{{ $project->image }}</span>
                                        @else
                                            No image selected &middot; JPG, PNG, WebP up to 2MB
                                        @endif
                                    </label>
                                    <input class="file-input" type="file" id="image" name="image" accept="image/*">
                                </div>
                            </div>
                            @if ($imageExists)
                            <label class="check" style="margin-top:10px;">
                                <input type="checkbox" name="remove_image" value="1">
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                Remove current image
                            </label>
                            @endif
                            @error('image') <span class="field__error" style="margin-top:10px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('demo_url') has-error @enderror">
                            <label class="field__label" for="demo_url">Demo URL</label>
                            <input class="input" type="url" id="demo_url" name="demo_url"
                                   value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://example.com">
                            @error('demo_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('source_url') has-error @enderror">
                            <label class="field__label" for="source_url">Source Code URL</label>
                            <input class="input" type="url" id="source_url" name="source_url"
                                   value="{{ old('source_url', $project->source_url) }}" placeholder="https://github.com/username/repo">
                            @error('source_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('technologies') has-error @enderror">
                            <label class="field__label" for="technologies">Technologies</label>
                            <input class="input" type="text" id="technologies" name="technologies"
                                   value="{{ old('technologies', $project->technologies) }}" placeholder="Laravel, MySQL, Vue">
                            <span class="field__hint">Comma separated, e.g. &ldquo;Laravel, MySQL, Vue&rdquo;.</span>
                            @error('technologies') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', $project->sort_order) }}" min="0">
                            <span class="field__hint">Lower numbers appear first.</span>
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field">
                            <label class="field__label">Visibility</label>
                            <label class="check">
                                <input type="checkbox" name="is_featured" value="1"
                                       {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                Feature this project
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.projects.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Update Project
                </button>
            </div>
        </form>

    </div>

@endsection
