@extends('admin.layout', ['title' => 'Add Training', 'subtitle' => 'Create a new training or certification entry.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Add Training</h2>
                <p>Add a training course, workshop, or certification.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.trainings.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Training
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.trainings.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card__head"><h3>Training Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('title') has-error @enderror">
                            <label class="field__label" for="title">Title <span class="req">*</span></label>
                            <input class="input" type="text" id="title" name="title"
                                   value="{{ old('title') }}" placeholder="e.g. Laravel Advanced Certification" required>
                            <span class="field__hint">A URL slug is auto-generated from the title.</span>
                            @error('title') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('organization') has-error @enderror">
                            <label class="field__label" for="organization">Organization / Issuer</label>
                            <input class="input" type="text" id="organization" name="organization"
                                   value="{{ old('organization') }}" placeholder="e.g. Laravel LLC">
                            @error('organization') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('duration') has-error @enderror">
                            <label class="field__label" for="duration">Duration</label>
                            <input class="input" type="text" id="duration" name="duration"
                                   value="{{ old('duration') }}" placeholder="e.g. 3 months, 40 hours">
                            @error('duration') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('start_year') has-error @enderror">
                            <label class="field__label" for="start_year">Start Year</label>
                            <input class="input" type="number" id="start_year" name="start_year"
                                   value="{{ old('start_year') }}" min="1900" max="{{ date('Y') + 5 }}" placeholder="2023">
                            @error('start_year') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('end_year') has-error @enderror">
                            <label class="field__label" for="end_year">End Year</label>
                            <input class="input" type="number" id="end_year" name="end_year"
                                   value="{{ old('end_year') }}" min="1900" max="{{ date('Y') + 5 }}" placeholder="2024">
                            @error('end_year') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('certificate_url') has-error @enderror">
                            <label class="field__label" for="certificate_url">Certificate URL</label>
                            <input class="input" type="url" id="certificate_url" name="certificate_url"
                                   value="{{ old('certificate_url') }}" placeholder="https://example.com/cert.pdf">
                            @error('certificate_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Short Description</label>
                            <textarea class="textarea rich-text" id="description" name="description" rows="3"
                                      placeholder="A brief summary shown on the training card.">{{ old('description') }}</textarea>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('long_description') has-error @enderror">
                            <label class="field__label" for="long_description">Full Description (Detail Page)</label>
                            <textarea class="textarea rich-text" id="long_description" name="long_description" rows="6"
                                      placeholder="Detailed description shown on the training detail page.">{{ old('long_description') }}</textarea>
                            @error('long_description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('image') has-error @enderror">
                            <label class="field__label">Training Image</label>
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
                                Feature this training
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.trainings.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create Training
                </button>
            </div>
        </form>

    </div>

@endsection

@include('admin.partials.rich-text')
