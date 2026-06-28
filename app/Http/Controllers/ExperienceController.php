<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the experiences.
     */
    public function index(): View
    {
        $experiences = Experience::orderBy('sort_order')
            ->orderByDesc('start_date')
            ->paginate(15);

        $totalExp = Experience::totalExperience();

        return view('admin.experiences.index', compact('experiences', 'totalExp'));
    }

    /**
     * Show the form for creating a new experience.
     */
    public function create(): View
    {
        return view('admin.experiences.create');
    }

    /**
     * Store a newly created experience in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        // A current role cannot have an end date.
        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        if (isset($validated['description'])) {
            $validated['description'] = (new HtmlSanitizer())->clean($validated['description']);
        }

        Experience::create($validated);

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Experience created successfully.');
    }

    /**
     * Show the form for editing the specified experience.
     */
    public function edit(Experience $experience): View
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    /**
     * Update the specified experience in storage.
     */
    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        if (isset($validated['description'])) {
            $validated['description'] = (new HtmlSanitizer())->clean($validated['description']);
        }

        $experience->update($validated);

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

    /**
     * Remove the specified experience from storage.
     */
    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }
}
