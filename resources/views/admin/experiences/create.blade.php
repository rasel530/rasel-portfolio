@extends('admin.layout', ['title' => 'Add Experience', 'subtitle' => 'Add a new work position to your timeline.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Add Experience</h2>
                <p>Describe a role you have held.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.experiences.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Experience
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.experiences.store') }}">
            @csrf

            <div class="card">
                <div class="card__head"><h3>Position Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field @error('position') has-error @enderror">
                            <label class="field__label" for="position">Position <span class="req">*</span></label>
                            <input class="input" type="text" id="position" name="position"
                                   value="{{ old('position') }}" placeholder="e.g. Junior Web Developer" required>
                            @error('position') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('company') has-error @enderror">
                            <label class="field__label" for="company">Company <span class="req">*</span></label>
                            <input class="input" type="text" id="company" name="company"
                                   value="{{ old('company') }}" placeholder="e.g. Tech Solutions Ltd." required>
                            @error('company') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('company_address') has-error @enderror">
                            <label class="field__label" for="company_address">Company Address</label>
                            <input class="input" type="text" id="company_address" name="company_address"
                                   value="{{ old('company_address') }}" placeholder="e.g. Dhaka, Bangladesh">
                            @error('company_address') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Description</label>
                            <textarea class="textarea rich-text" id="description" name="description" rows="5"
                                      placeholder="Summarize your responsibilities and achievements.">{{ old('description') }}</textarea>
                            <span class="field__hint">Tip: select text to <strong>bold</strong> / <em>italicize</strong>; use Enter for paragraphs and the toolbar for bullet or numbered lists.</span>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('start_date') has-error @enderror">
                            <label class="field__label" for="start_date">Start Date <span class="req">*</span></label>
                            <input class="input" type="date" id="start_date" name="start_date"
                                   value="{{ old('start_date') }}" required>
                            @error('start_date') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('end_date') has-error @enderror">
                            <label class="field__label" for="end_date">End Date</label>
                            <input class="input" type="date" id="end_date" name="end_date"
                                   value="{{ old('end_date') }}">
                            <span class="field__hint">Leave empty if this is your current role.</span>
                            @error('end_date') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full">
                            <label class="check">
                                <input type="checkbox" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}>
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                I currently work here
                            </label>
                        </div>

                        <div class="field field--full @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', '0') }}" min="0">
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.experiences.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create Experience
                </button>
            </div>
        </form>

    </div>

@endsection

@include('admin.partials.rich-text')
