@extends('admin.layout', ['title' => 'Add Project', 'subtitle' => 'Create a new project entry.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Add Project</h2>
                <p>Showcase a project you have built.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.projects.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Projects
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card__head"><h3>Project Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('title') has-error @enderror">
                            <label class="field__label" for="title">Title <span class="req">*</span></label>
                            <input class="input" type="text" id="title" name="title"
                                   value="{{ old('title') }}" placeholder="e.g. Portfolio Website" required>
                            @error('title') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Description <span class="req">*</span></label>
                            <textarea class="textarea rich-text" id="description" name="description" rows="5"
                                      placeholder="Describe what the project does and your role." required>{{ old('description') }}</textarea>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('image') has-error @enderror">
                            <label class="field__label">Project Image</label>
                            <div class="file-upload">
                                <div class="file-preview">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                </div>
                                <div class="file-input-wrap">
                                    <label class="file-drop" for="image">
                                        <strong>Click to upload an image</strong>
                                        Optional &middot; JPG, PNG, WebP up to 2MB
                                    </label>
                                    <input class="file-input" type="file" id="image" name="image" accept="image/*">
                                </div>
                            </div>
                            @error('image') <span class="field__error" style="margin-top:10px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('demo_url') has-error @enderror">
                            <label class="field__label" for="demo_url">Demo URL</label>
                            <input class="input" type="url" id="demo_url" name="demo_url"
                                   value="{{ old('demo_url') }}" placeholder="https://example.com">
                            @error('demo_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('source_url') has-error @enderror">
                            <label class="field__label" for="source_url">Source Code URL</label>
                            <input class="input" type="url" id="source_url" name="source_url"
                                   value="{{ old('source_url') }}" placeholder="https://github.com/username/repo">
                            @error('source_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('technologies') has-error @enderror">
                            <label class="field__label" for="technologies">Technologies</label>
                            <input class="input" type="text" id="technologies" name="technologies"
                                   value="{{ old('technologies') }}" placeholder="Laravel, MySQL, Vue">
                            <span class="field__hint">Comma separated, e.g. &ldquo;Laravel, MySQL, Vue&rdquo;.</span>
                            @error('technologies') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', '0') }}" min="0">
                            <span class="field__hint">Lower numbers appear first.</span>
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field">
                            <label class="field__label">Visibility</label>
                            <label class="check">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create Project
                </button>
            </div>
        </form>

    </div>

@endsection

@include('admin.partials.rich-text')
