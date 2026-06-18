<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Redirect to the single profile record's edit page.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.profiles.edit', optional(Profile::first())->id ?? 1);
    }

    /**
     * Show the form for editing the single profile record.
     */
    public function edit($id): View
    {
        $profile = Profile::firstOrCreate(['id' => 1], [
            'name' => 'Rasel Bepari',
            'title' => 'Developer',
            'tagline' => '',
            'summary' => '',
            'email' => 'admin@raselbepari.com',
            'phone' => '',
            'address' => '',
        ]);

        return view('admin.profiles.edit', compact('profile'));
    }

    /**
     * Update the single profile record in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $profile = Profile::firstOrCreate(['id' => 1], [
            'name' => 'Rasel Bepari',
            'title' => 'Developer',
            'tagline' => '',
            'summary' => '',
            'email' => 'admin@raselbepari.com',
            'phone' => '',
            'address' => '',
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:1024'],
            'logo_dark' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:1024'],
            'resume_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_logo_dark' => ['nullable', 'boolean'],
            'remove_resume' => ['nullable', 'boolean'],
            'resume_url' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
        ]);

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            $validated['photo'] = $this->storeWithOriginalName($request->file('photo'), 'profile');
        } elseif ($request->boolean('remove_photo') && $profile->photo) {
            Storage::disk('public')->delete($profile->photo);
            $validated['photo'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }

            $validated['logo'] = $this->storeWithOriginalName($request->file('logo'), 'logo');
        } elseif ($request->boolean('remove_logo') && $profile->logo) {
            Storage::disk('public')->delete($profile->logo);
            $validated['logo'] = null;
        }

        // Dark-mode logo
        if ($request->hasFile('logo_dark')) {
            if ($profile->logo_dark) {
                Storage::disk('public')->delete($profile->logo_dark);
            }

            $validated['logo_dark'] = $this->storeWithOriginalName($request->file('logo_dark'), 'logo');
        } elseif ($request->boolean('remove_logo_dark') && $profile->logo_dark) {
            Storage::disk('public')->delete($profile->logo_dark);
            $validated['logo_dark'] = null;
        }

        // CV/Resume: file upload takes priority over URL field
        if ($request->hasFile('resume_file')) {
            if ($profile->resume_url) {
                Storage::disk('public')->delete($profile->resume_url);
            }
            $validated['resume_url'] = $this->storeWithOriginalName($request->file('resume_file'), 'resume');
        } elseif ($request->boolean('remove_resume') && $profile->resume_url) {
            Storage::disk('public')->delete($profile->resume_url);
            $validated['resume_url'] = null;
        }

        unset($validated['remove_photo'], $validated['remove_logo'], $validated['remove_logo_dark'], $validated['remove_resume'], $validated['resume_file']);

        $profile->update($validated);

        return redirect()
            ->route('admin.profiles.edit', $profile)
            ->with('success', 'Profile updated successfully.');
    }
}
