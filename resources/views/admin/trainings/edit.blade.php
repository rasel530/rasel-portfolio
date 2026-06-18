@extends('admin.layout', ['title' => 'Edit Training', 'subtitle' => 'Update this training entry.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit Training</h2>
                <p>Updating &ldquo;{{ $training->title }}&rdquo;</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.trainings.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Training
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.trainings.update', $training->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card__head"><h3>Training Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('title') has-error @enderror">
                            <label class="field__label" for="title">Title <span class="req">*</span></label>
                            <input class="input" type="text" id="title" name="title"
                                   value="{{ old('title', $training->title) }}" required>
                            @error('title') <span class="field__error">{{ $message }}</span> @enderror
                            @if($training->slug)
                            <span class="field__hint">Public URL: <code>{{ route('training.show', $training->slug) }}</code></span>
                            @endif
                        </div>

                        <div class="field @error('organization') has-error @enderror">
                            <label class="field__label" for="organization">Organization / Issuer</label>
                            <input class="input" type="text" id="organization" name="organization"
                                   value="{{ old('organization', $training->organization) }}">
                            @error('organization') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('duration') has-error @enderror">
                            <label class="field__label" for="duration">Duration</label>
                            <input class="input" type="text" id="duration" name="duration"
                                   value="{{ old('duration', $training->duration) }}">
                            @error('duration') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('start_year') has-error @enderror">
                            <label class="field__label" for="start_year">Start Year</label>
                            <input class="input" type="number" id="start_year" name="start_year"
                                   value="{{ old('start_year', $training->start_year) }}" min="1900" max="{{ date('Y') + 5 }}">
                            @error('start_year') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('end_year') has-error @enderror">
                            <label class="field__label" for="end_year">End Year</label>
                            <input class="input" type="number" id="end_year" name="end_year"
                                   value="{{ old('end_year', $training->end_year) }}" min="1900" max="{{ date('Y') + 5 }}">
                            @error('end_year') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('certificate_url') has-error @enderror">
                            <label class="field__label" for="certificate_url">Certificate URL</label>
                            <input class="input" type="url" id="certificate_url" name="certificate_url"
                                   value="{{ old('certificate_url', $training->certificate_url) }}">
                            @error('certificate_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Short Description</label>
                            <textarea class="textarea" id="description" name="description" rows="3">{{ old('description', $training->description) }}</textarea>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('long_description') has-error @enderror">
                            <label class="field__label" for="long_description">Full Description (Detail Page)</label>
                            <textarea class="textarea" id="long_description" name="long_description" rows="6">{{ old('long_description', $training->long_description) }}</textarea>
                            <span class="field__hint">Line breaks are preserved.</span>
                            @error('long_description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('image') has-error @enderror">
                            <label class="field__label">Training Image</label>
                            @if($training->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($training->image))
                            <div class="file-preview" style="margin-bottom:12px;max-width:300px;">
                                <img src="{{ Storage::url($training->image) }}" alt="{{ $training->title }}" style="object-fit:cover;width:100%;height:160px;border-radius:8px;">
                            </div>
                            @endif
                            <div class="file-upload">
                                <div class="file-input-wrap">
                                    <label class="file-drop" for="image">
                                        <strong>Click to upload a new image</strong>
                                        Optional &middot; JPG, PNG, WebP up to 2MB
                                    </label>
                                    <input class="file-input" type="file" id="image" name="image" accept="image/*">
                                </div>
                            </div>
                            @error('image') <span class="field__error" style="margin-top:10px;">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', $training->sort_order) }}" min="0">
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field">
                            <label class="field__label">Visibility</label>
                            <label class="check">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $training->is_featured) ? 'checked' : '' }}>
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                Feature this training
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.trainings.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Changes
                </button>
            </div>
        </form>

    </div>

@endsection
