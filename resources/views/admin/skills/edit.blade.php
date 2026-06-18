@extends('admin.layout', ['title' => 'Edit Skill', 'subtitle' => 'Update this skill entry.'])

@php($prof = (int) old('proficiency', $skill->proficiency))

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit Skill</h2>
                <p>Updating &ldquo;{{ $skill->name }}&rdquo;</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.skills.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Skills
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.skills.update', $skill->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card__head"><h3>Skill Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('name') has-error @enderror">
                            <label class="field__label" for="name">Skill Name <span class="req">*</span></label>
                            <input class="input" type="text" id="name" name="name"
                                   value="{{ old('name', $skill->name) }}" required>
                            @error('name') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('category') has-error @enderror">
                            <label class="field__label" for="category">Category <span class="req">*</span></label>
                            <input class="input" type="text" id="category" name="category"
                                   value="{{ old('category', $skill->category) }}" placeholder="e.g. Backend, Frontend, Tools"
                                   list="existingCategories" autocomplete="off" required>
                            <datalist id="existingCategories">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            <span class="field__hint">Pick an existing category or type a new one.</span>
                            @error('category') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('sort_order') has-error @enderror">
                            <label class="field__label" for="sort_order">Sort Order</label>
                            <input class="input" type="number" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', $skill->sort_order) }}" min="0">
                            <span class="field__hint">Lower numbers appear first.</span>
                            @error('sort_order') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full range-field @error('proficiency') has-error @enderror">
                            <label class="field__label" for="proficiency">Proficiency <span class="req">*</span></label>
                            <div class="range-row">
                                <input class="range" type="range" id="proficiency" name="proficiency"
                                       min="0" max="100" step="1" value="{{ $prof }}"
                                       oninput="document.getElementById('proficiencyValue').textContent = this.value + '%';">
                                <span class="range-value" id="proficiencyValue">{{ $prof }}%</span>
                            </div>
                            @error('proficiency') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.skills.index') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Changes
                </button>
            </div>
        </form>

    </div>

@endsection
