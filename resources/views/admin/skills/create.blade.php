@extends('admin.layout', ['title' => 'Add Skill', 'subtitle' => 'Create a new skill entry.'])

@php($prof = (int) old('proficiency', 50))

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Add Skill</h2>
                <p>Fill in the details below to add a new skill.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.skills.index') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Skills
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.skills.store') }}">
            @csrf

            <div class="card">
                <div class="card__head"><h3>Skill Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('name') has-error @enderror">
                            <label class="field__label" for="name">Skill Name <span class="req">*</span></label>
                            <input class="input" type="text" id="name" name="name"
                                   value="{{ old('name') }}" placeholder="e.g. Laravel" required>
                            @error('name') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('category') has-error @enderror">
                            <label class="field__label" for="category">Category <span class="req">*</span></label>
                            <input class="input" type="text" id="category" name="category"
                                   value="{{ old('category') }}" placeholder="e.g. Backend, Frontend, Tools"
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
                                   value="{{ old('sort_order', '0') }}" min="0">
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create Skill
                </button>
            </div>
        </form>

    </div>

@endsection
