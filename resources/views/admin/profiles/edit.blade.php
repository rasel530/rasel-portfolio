@extends('admin.layout', ['title' => 'Edit Profile', 'subtitle' => 'Update your personal information shown across the portfolio.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Edit Profile</h2>
                <p>Update your personal information shown across the portfolio.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <form id="profileForm" method="POST" action="{{ route('admin.profiles.update', $profile->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Profile Photo</h3>
                        <p>This image appears in the hero and about sections.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="file-upload">
                        @php
                            $photoExists = $profile->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->photo);
                        @endphp
                        <div class="file-preview" id="photoPreview">
                            @if ($photoExists)
                                <img src="{{ Storage::url($profile->photo) }}" alt="Current photo">
                            @elseif ($profile->photo)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            @endif
                        </div>
                        <div class="file-input-wrap">
                            <label class="file-drop" for="photo" id="photoLabel">
                                <strong>Click to upload a new photo</strong>
                                @if ($profile->photo)
                                    Current: <span class="text-muted">{{ $profile->photo }}</span>
                                @else
                                    No photo selected · JPG, PNG, WebP up to 2MB
                                @endif
                            </label>
                            <input class="file-input" type="file" id="photo" name="photo" accept="image/*">
                        </div>
                    </div>
                    @if ($photoExists)
                    <label class="check" style="margin-top:10px;">
                        <input type="checkbox" name="remove_photo" value="1">
                        <span class="check__box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                        Remove current photo
                    </label>
                    @endif
                    @error('photo') <div class="field__error" style="margin-top:10px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Site Logo</h3>
                        <p>Upload a logo for light and dark mode. If you upload only one, it is used for both themes. Recommended: 200×50px transparent PNG/SVG, max 1MB.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        {{-- ===== Light Logo ===== --}}
                        @php
                            $logoLightExists = $profile->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->logo);
                        @endphp
                        <div class="field @error('logo') has-error @enderror">
                            <label class="field__label">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/></svg>
                                Light Mode Logo
                            </label>
                            <div class="file-upload">
                                <div class="file-preview" id="logoPreview" style="max-width:200px;background:#fff;padding:12px;border-radius:8px;">
                                    @if ($logoLightExists)
                                        <img src="{{ Storage::url($profile->logo) }}" alt="Light logo" style="object-fit:contain;width:100%;height:auto;max-height:50px;">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:32px;height:32px;margin:0 auto;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    @endif
                                </div>
                                <div class="file-input-wrap">
                                    <label class="file-drop" id="logoLabel" for="logo">
                                        <strong>Upload light logo</strong>
                                        @if ($logoLightExists)
                                            <span class="text-muted">{{ \Illuminate\Support\Str::afterLast($profile->logo, '/') }}</span>
                                        @else
                                            <span class="text-muted">No logo · PNG, SVG, WebP</span>
                                        @endif
                                    </label>
                                    <input class="file-input" type="file" id="logo" name="logo" accept="image/*">
                                </div>
                            </div>
                            @if ($logoLightExists)
                            <label class="check" style="margin-top:10px;">
                                <input type="checkbox" name="remove_logo" value="1">
                                <span class="check__box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                                Remove
                            </label>
                            @endif
                            @error('logo') <div class="field__error" style="margin-top:10px;">{{ $message }}</div> @enderror
                        </div>

                        {{-- ===== Dark Logo ===== --}}
                        @php
                            $logoDarkExists = $profile->logo_dark && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->logo_dark);
                        @endphp
                        <div class="field @error('logo_dark') has-error @enderror">
                            <label class="field__label">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                                Dark Mode Logo
                            </label>
                            <div class="file-upload">
                                <div class="file-preview" id="logoDarkPreview" style="max-width:200px;background:#0f172a;padding:12px;border-radius:8px;">
                                    @if ($logoDarkExists)
                                        <img src="{{ Storage::url($profile->logo_dark) }}" alt="Dark logo" style="object-fit:contain;width:100%;height:auto;max-height:50px;">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:32px;height:32px;margin:0 auto;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    @endif
                                </div>
                                <div class="file-input-wrap">
                                    <label class="file-drop" id="logoDarkLabel" for="logo_dark">
                                        <strong>Upload dark logo</strong>
                                        @if ($logoDarkExists)
                                            <span class="text-muted">{{ \Illuminate\Support\Str::afterLast($profile->logo_dark, '/') }}</span>
                                        @else
                                            <span class="text-muted">No logo · PNG, SVG, WebP</span>
                                        @endif
                                    </label>
                                    <input class="file-input" type="file" id="logo_dark" name="logo_dark" accept="image/*">
                                </div>
                            </div>
                            @if ($logoDarkExists)
                            <label class="check" style="margin-top:10px;">
                                <input type="checkbox" name="remove_logo_dark" value="1">
                                <span class="check__box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                                Remove
                            </label>
                            @endif
                            @error('logo_dark') <div class="field__error" style="margin-top:10px;">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card section">
                <div class="card__head"><h3>Basic Information</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field field--full @error('name') has-error @enderror">
                            <label class="field__label" for="name">Full Name <span class="req">*</span></label>
                            <input class="input" type="text" id="name" name="name"
                                   value="{{ old('name', $profile->name) }}" required>
                            @error('name') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('title') has-error @enderror">
                            <label class="field__label" for="title">Professional Title <span class="req">*</span></label>
                            <input class="input" type="text" id="title" name="title"
                                   value="{{ old('title', $profile->title) }}" required>
                            @error('title') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('tagline') has-error @enderror">
                            <label class="field__label" for="tagline">Tagline</label>
                            <input class="input" type="text" id="tagline" name="tagline"
                                   value="{{ old('tagline', $profile->tagline) }}"
                                   placeholder="A short catchy phrase">
                            @error('tagline') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('summary') has-error @enderror">
                            <label class="field__label" for="summary">Summary / About Me</label>
                            <textarea class="textarea rich-text" id="summary" name="summary" rows="10"
                                      placeholder="Write a few sentences about yourself, your background and your goals.">{{ old('summary', $profile->summary) }}</textarea>
                            <span class="field__hint">Tip: select text to <strong>bold</strong> or <em>italicize</em> it; press Enter to start a new paragraph.</span>
                            @error('summary') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="card section">
                <div class="card__head"><h3>Contact Details</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field @error('email') has-error @enderror">
                            <label class="field__label" for="email">Email <span class="req">*</span></label>
                            <input class="input" type="email" id="email" name="email"
                                   value="{{ old('email', $profile->email) }}" required>
                            @error('email') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('phone') has-error @enderror">
                            <label class="field__label" for="phone">Phone</label>
                            <input class="input" type="text" id="phone" name="phone"
                                   value="{{ old('phone', $profile->phone) }}">
                            @error('phone') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('address') has-error @enderror">
                            <label class="field__label" for="address">Address</label>
                            <textarea class="textarea" id="address" name="address" rows="3">{{ old('address', $profile->address) }}</textarea>
                            @error('address') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="card section">
                <div class="card__head"><h3>Social Links &amp; Resume</h3></div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field @error('facebook') has-error @enderror">
                            <label class="field__label" for="facebook">Facebook URL</label>
                            <input class="input" type="url" id="facebook" name="facebook"
                                   value="{{ old('facebook', $profile->facebook) }}"
                                   placeholder="https://facebook.com/username">
                            @error('facebook') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('linkedin') has-error @enderror">
                            <label class="field__label" for="linkedin">LinkedIn URL</label>
                            <input class="input" type="url" id="linkedin" name="linkedin"
                                   value="{{ old('linkedin', $profile->linkedin) }}"
                                   placeholder="https://linkedin.com/in/username">
                            @error('linkedin') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('github') has-error @enderror">
                            <label class="field__label" for="github">GitHub URL</label>
                            <input class="input" type="url" id="github" name="github"
                                   value="{{ old('github', $profile->github) }}"
                                   placeholder="https://github.com/username">
                            @error('github') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field @error('twitter') has-error @enderror">
                            <label class="field__label" for="twitter">Twitter / X URL</label>
                            <input class="input" type="url" id="twitter" name="twitter"
                                   value="{{ old('twitter', $profile->twitter) }}"
                                   placeholder="https://twitter.com/username">
                            @error('twitter') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('resume_url') has-error @enderror">
                            <label class="field__label" for="resume_url">Resume / CV URL</label>
                            <input class="input" type="url" id="resume_url" name="resume_url"
                                   value="{{ old('resume_url', filter_var($profile->resume_url, FILTER_VALIDATE_URL) ? $profile->resume_url : '') }}"
                                   placeholder="https://example.com/resume.pdf">
                            <span class="field__hint">External link. Leave blank if uploading a file below.</span>
                            @error('resume_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field field--full @error('resume_file') has-error @enderror">
                            <label class="field__label" for="resume_file">Or Upload CV / Resume File</label>
                            <div class="file-input-wrap">
                                <label class="file-drop" for="resume_file" id="resumeLabel">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 12 15 15"/></svg>
                                    <strong>Click to upload your CV</strong>
                                    @if ($profile->resume_url && !filter_var($profile->resume_url, FILTER_VALIDATE_URL) && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->resume_url))
                                        Current: <span class="text-muted">{{ \Illuminate\Support\Str::afterLast($profile->resume_url, '/') }}</span>
                                    @else
                                        No file selected · PDF, DOC, DOCX up to 5MB
                                    @endif
                                </label>
                                <input class="file-input" type="file" id="resume_file" name="resume_file" accept=".pdf,.doc,.docx">
                            </div>
                            @if ($profile->resume_url && !filter_var($profile->resume_url, FILTER_VALIDATE_URL) && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->resume_url))
                            <label class="check" style="margin-top:10px;">
                                <input type="checkbox" name="remove_resume" value="1">
                                <span class="check__box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                                Remove current CV file
                            </label>
                            @endif
                            @error('resume_file') <span class="field__error" style="margin-top:10px;display:block;">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Profile
                </button>
            </div>
        </form>

    </div>

@endsection

@include('admin.partials.rich-text')

@push('scripts')
<script>
(function () {
    var input = document.getElementById('photo');
    var preview = document.getElementById('photoPreview');
    var label = document.getElementById('photoLabel');
    if (!input || !preview) return;

    var currentUrl = null;

    input.addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        if (!file) return;

        // Validate it's an image
        if (!file.type.match(/^image\//)) {
            alert('Please select an image file (JPG, PNG, WebP).');
            input.value = '';
            return;
        }

        // Revoke previous preview URL if any
        if (currentUrl) {
            URL.revokeObjectURL(currentUrl);
        }

        // Create a temporary URL for the selected file
        currentUrl = URL.createObjectURL(file);

        // Replace preview content with the selected image
        preview.innerHTML = '<img src="' + currentUrl + '" alt="Selected photo" style="object-fit:cover;width:100%;height:100%;">';

        // Update label text to show the filename
        if (label) {
            var strong = label.querySelector('strong');
            var hint = label.querySelector('.text-muted');
            if (strong) strong.textContent = 'Photo selected!';
            if (hint) {
                hint.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            } else {
                // If there was no .text-muted span, append the filename
                label.innerHTML += '<br><span class="text-muted">' + file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)</span>';
            }
        }

        // Add a subtle "preview" indicator
        preview.style.opacity = '0';
        preview.style.transition = 'opacity 0.3s ease';
        setTimeout(function () { preview.style.opacity = '1'; }, 50);
    });
})();
</script>
<script>
    // Logo live preview
    var logoInput = document.getElementById('logo');
    var logoPreview = document.getElementById('logoPreview');
    var logoLabel = document.getElementById('logoLabel');
    if (logoInput && logoPreview) {
        var currentLogoUrl = null;
        logoInput.addEventListener('change', function (e) {
            var file = e.target.files && e.target.files[0];
            if (!file) return;
            if (!file.type.match(/^image\//)) {
                alert('Please select an image file.');
                logoInput.value = '';
                return;
            }
            if (currentLogoUrl) URL.revokeObjectURL(currentLogoUrl);
            currentLogoUrl = URL.createObjectURL(file);
            logoPreview.innerHTML = '<img src="' + currentLogoUrl + '" alt="Selected logo" style="object-fit:contain;width:100%;height:auto;max-height:60px;">';
            if (logoLabel) {
                var strong = logoLabel.querySelector('strong');
                if (strong) strong.textContent = 'Logo selected!';
                var hint = logoLabel.querySelector('.text-muted');
                if (hint) {
                    hint.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
                }
            }
            logoPreview.style.opacity = '0';
            logoPreview.style.transition = 'opacity 0.3s ease';
            setTimeout(function () { logoPreview.style.opacity = '1'; }, 50);
        });
    }
</script>
<script>
    // Dark logo live preview
    var logoDarkInput = document.getElementById('logo_dark');
    var logoDarkPreview = document.getElementById('logoDarkPreview');
    var logoDarkLabel = document.getElementById('logoDarkLabel');
    if (logoDarkInput && logoDarkPreview) {
        var currentDarkLogoUrl = null;
        logoDarkInput.addEventListener('change', function (e) {
            var file = e.target.files && e.target.files[0];
            if (!file) return;
            if (!file.type.match(/^image\//)) {
                alert('Please select an image file.');
                logoDarkInput.value = '';
                return;
            }
            if (currentDarkLogoUrl) URL.revokeObjectURL(currentDarkLogoUrl);
            currentDarkLogoUrl = URL.createObjectURL(file);
            logoDarkPreview.innerHTML = '<img src="' + currentDarkLogoUrl + '" alt="Selected dark logo" style="object-fit:contain;width:100%;height:auto;max-height:50px;">';
            if (logoDarkLabel) {
                var strong = logoDarkLabel.querySelector('strong');
                if (strong) strong.textContent = 'Dark logo selected!';
                var hint = logoDarkLabel.querySelector('.text-muted');
                if (hint) {
                    hint.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
                }
            }
            logoDarkPreview.style.opacity = '0';
            logoDarkPreview.style.transition = 'opacity 0.3s ease';
            setTimeout(function () { logoDarkPreview.style.opacity = '1'; }, 50);
        });
    }
</script>
<script>
    // Resume file label update
    (function () {
        var resumeInput = document.getElementById('resume_file');
        var resumeLabel = document.getElementById('resumeLabel');
        if (resumeInput && resumeLabel) {
            resumeInput.addEventListener('change', function () {
                var strong = resumeLabel.querySelector('strong');
                if (strong) strong.textContent = 'File selected!';
                var hint = resumeLabel.querySelector('.text-muted');
                if (hint && this.files && this.files[0]) {
                    hint.textContent = this.files[0].name + ' (' + (this.files[0].size / 1024).toFixed(0) + ' KB)';
                }
            });
        }
    })();
</script>
@endpush
