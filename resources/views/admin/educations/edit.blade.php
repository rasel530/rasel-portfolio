@extends('admin.layout', ['title' => 'Edit Education', 'subtitle' => 'Update this education record.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit Education</h2>
                <p>Updating &ldquo;{{ $education->degree }}&rdquo;</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.educations.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Education
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.educations.update', $education->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card__head"><h3>Education Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field @error('degree') has-error @enderror">
                            <label class="field__label" for="degree">Degree <span class="req">*</span></label>
                            <input class="input" type="text" id="degree" name="degree"
                                   value="{{ old('degree', $education->degree) }}" required>
                            @error('degree') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('institution') has-error @enderror">
                            <label class="field__label" for="institution">Institution <span class="req">*</span></label>
                            <input class="input" type="text" id="institution" name="institution"
                                   value="{{ old('institution', $education->institution) }}" required>
                            @error('institution') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('description') has-error @enderror">
                            <label class="field__label" for="description">Description</label>
                            <textarea class="textarea" id="description" name="description" rows="4">{{ old('description', $education->description) }}</textarea>
                            @error('description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('start_year') has-error @enderror">
                            <label class="field__label" for="start_year">Start Year <span class="req">*</span></label>
                            <input class="input" type="number" id="start_year" name="start_year"
                                   value="{{ old('start_year', $education->start_year) }}"
                                   min="1900" max="{{ date('Y') }}" required>
                            @error('start_year') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('end_year') has-error @enderror">
                            <label class="field__label" for="end_year">End Year</label>
                            <input class="input" type="number" id="end_year" name="end_year"
                                   value="{{ old('end_year', $education->end_year) }}"
                                   min="1900" max="{{ (int) date('Y') + 10 }}">
                            <span class="field__hint" id="end_year_hint">Leave empty if you are currently studying here.</span>
                            @error('end_year') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('result') has-error @enderror">
                            <label class="field__label" for="result">Result</label>
                            <input class="input" type="text" id="result" name="result"
                                   value="{{ old('result', $education->result) }}" placeholder="e.g. CGPA 3.75">
                            @error('result') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', $education->sort_order) }}" min="0">
                            <span class="field__hint">Lower numbers appear first.</span>
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full">
                            <label class="check">
                                <input type="checkbox" id="is_current" name="is_current" value="1"
                                       {{ old('is_current', $education->is_current) ? 'checked' : '' }}>
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                I am currently studying here
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.educations.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Update Education
                </button>
            </div>
        </form>

    </div>

@endsection

@push('scripts')
    <script>
        (function () {
            var checkbox = document.getElementById('is_current');
            var endYear  = document.getElementById('end_year');
            var hint     = document.getElementById('end_year_hint');

            function applyState(clearValue) {
                if (!checkbox) return;
                if (checkbox.checked) {
                    if (endYear) endYear.disabled = true;
                    if (clearValue && endYear) endYear.value = '';
                    if (hint) hint.textContent = 'Not required while you are still studying.';
                } else {
                    if (endYear) endYear.disabled = false;
                    if (hint) hint.textContent = 'Leave empty if you are currently studying here.';
                }
            }

            if (checkbox) {
                checkbox.addEventListener('change', function () { applyState(true); });
                applyState(false);
            }
        })();
    </script>
@endpush
