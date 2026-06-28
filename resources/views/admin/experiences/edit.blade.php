@extends('admin.layout', ['title' => 'Edit Experience', 'subtitle' => 'Update this work position.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit Experience</h2>
                <p>Updating &ldquo;{{ $experience->position }}&rdquo;</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.experiences.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Experience
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.experiences.update', $experience->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card__head"><h3>Position Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field @error('position') has-error @enderror">
                            <label class="field__label" for="position">Position <span class="req">*</span></label>
                            <input class="input" type="text" id="position" name="position"
                                   value="{{ old('position', $experience->position) }}" required>
                            @error('position') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('company') has-error @enderror">
                            <label class="field__label" for="company">Company <span class="req">*</span></label>
                            <input class="input" type="text" id="company" name="company"
                                   value="{{ old('company', $experience->company) }}" required>
                            @error('company') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('company_address') has-error @enderror">
                            <label class="field__label" for="company_address">Company Address</label>
                            <input class="input" type="text" id="company_address" name="company_address"
                                   value="{{ old('company_address', $experience->company_address) }}"
                                   placeholder="e.g. Dhaka, Bangladesh">
                            @error('company_address') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Description</label>
                            <textarea class="textarea rich-text" id="description" name="description" rows="5">{{ old('description', $experience->description) }}</textarea>
                            <span class="field__hint">Tip: select text to <strong>bold</strong> / <em>italicize</em>; use Enter for paragraphs and the toolbar for bullet or numbered lists.</span>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('start_date') has-error @enderror">
                            <label class="field__label" for="start_date">Start Date <span class="req">*</span></label>
                            <input class="input" type="date" id="start_date" name="start_date"
                                   value="{{ old('start_date', $experience->start_date ? $experience->start_date->format('Y-m-d') : '') }}" required>
                            @error('start_date') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('end_date') has-error @enderror">
                            <label class="field__label" for="end_date">End Date</label>
                            <input class="input" type="date" id="end_date" name="end_date"
                                   value="{{ old('end_date', $experience->end_date ? $experience->end_date->format('Y-m-d') : '') }}">
                            <span class="field__hint">Leave empty if this is your current role.</span>
                            @error('end_date') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full">
                            <label class="check">
                                <input type="checkbox" name="is_current" value="1"
                                       {{ old('is_current', $experience->is_current) ? 'checked' : '' }}>
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                I currently work here
                            </label>
                        </div>

                        <div class="field field--full @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', $experience->sort_order) }}" min="0">
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.experiences.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Changes
                </button>
            </div>
        </form>

    </div>

@endsection

@include('admin.partials.rich-text')
