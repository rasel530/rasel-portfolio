<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    /**
     * Display a listing of the educations.
     */
    public function index(): View
    {
        $educations = Education::orderBy('sort_order')
            ->orderByDesc('start_year')
            ->paginate(15);

        return view('admin.educations.index', compact('educations'));
    }

    /**
     * Show the form for creating a new education record.
     */
    public function create(): View
    {
        return view('admin.educations.create');
    }

    /**
     * Store a newly created education record in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'start_year' => ['required', 'integer', 'min:1900', 'max:' . (int) date('Y')],
            'end_year' => ['nullable', 'integer', 'gte:start_year', 'max:' . ((int) date('Y') + 10)],
            'is_current' => ['sometimes', 'boolean'],
            'result' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        if ($validated['is_current']) {
            $validated['end_year'] = null;
        }

        if (isset($validated['description'])) {
            $validated['description'] = (new HtmlSanitizer())->clean($validated['description']);
        }

        Education::create($validated);

        return redirect()
            ->route('admin.educations.index')
            ->with('success', 'Education record created successfully.');
    }

    /**
     * Show the form for editing the specified education record.
     */
    public function edit(Education $education): View
    {
        return view('admin.educations.edit', compact('education'));
    }

    /**
     * Update the specified education record in storage.
     */
    public function update(Request $request, Education $education): RedirectResponse
    {
        $validated = $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'start_year' => ['required', 'integer', 'min:1900', 'max:' . (int) date('Y')],
            'end_year' => ['nullable', 'integer', 'gte:start_year', 'max:' . ((int) date('Y') + 10)],
            'is_current' => ['sometimes', 'boolean'],
            'result' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        if ($validated['is_current']) {
            $validated['end_year'] = null;
        }

        if (isset($validated['description'])) {
            $validated['description'] = (new HtmlSanitizer())->clean($validated['description']);
        }

        $education->update($validated);

        return redirect()
            ->route('admin.educations.index')
            ->with('success', 'Education record updated successfully.');
    }

    /**
     * Remove the specified education record from storage.
     */
    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();

        return redirect()
            ->route('admin.educations.index')
            ->with('success', 'Education record deleted successfully.');
    }
}
