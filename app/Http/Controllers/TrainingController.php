<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TrainingController extends Controller
{
    // ── Admin ─────────────────────────────────────────────────────────

    public function index(): View
    {
        $trainings = Training::orderBy('sort_order')
            ->latest('created_at')
            ->paginate(15);

        return view('admin.trainings.index', compact('trainings'));
    }

    public function create(): View
    {
        return view('admin.trainings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'long_description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'certificate_url' => ['nullable', 'url', 'max:255'],
            'duration' => ['nullable', 'string', 'max:100'],
            'start_year' => ['nullable', 'integer', 'min:1900', 'max:' . ((int) date('Y') + 5)],
            'end_year' => ['nullable', 'integer', 'min:1900', 'max:' . ((int) date('Y') + 5)],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeWithOriginalName($request->file('image'), 'trainings');
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $validated = $this->sanitizeTrainingText($validated);

        Training::create($validated);

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training created successfully.');
    }

    public function edit(Training $training): View
    {
        return view('admin.trainings.edit', compact('training'));
    }

    public function update(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'long_description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'certificate_url' => ['nullable', 'url', 'max:255'],
            'duration' => ['nullable', 'string', 'max:100'],
            'start_year' => ['nullable', 'integer', 'min:1900', 'max:' . ((int) date('Y') + 5)],
            'end_year' => ['nullable', 'integer', 'min:1900', 'max:' . ((int) date('Y') + 5)],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            if ($training->image) {
                Storage::disk('public')->delete($training->image);
            }
            $validated['image'] = $this->storeWithOriginalName($request->file('image'), 'trainings');
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $validated = $this->sanitizeTrainingText($validated);

        $training->update($validated);

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training updated successfully.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        if ($training->image) {
            Storage::disk('public')->delete($training->image);
        }

        $training->delete();

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training deleted successfully.');
    }

    // ── Public ────────────────────────────────────────────────────────

    /**
     * Show a single training detail page.
     */
    public function show(string $slug): View
    {
        $training = Training::where('slug', $slug)->firstOrFail();
        $related = Training::where('id', '!=', $training->id)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        $profile = \App\Models\Profile::first();
        $siteSetting = \App\Models\SiteSetting::first();

        return view('portfolio.training-detail', compact('training', 'related', 'profile', 'siteSetting'));
    }

    /**
     * Sanitize the rich-text fields rendered raw on the public site.
     */
    private function sanitizeTrainingText(array $validated): array
    {
        $sanitizer = new HtmlSanitizer();

        foreach (['description', 'long_description'] as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = $sanitizer->clean($validated[$field]);
            }
        }

        return $validated;
    }
}
