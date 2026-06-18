<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Render the single-page portfolio.
     */
    public function index(): View
    {
        $profile = Profile::first();

        $skills = Skill::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $experiences = Experience::orderBy('sort_order')
            ->orderByDesc('start_date')
            ->get();

        $educations = Education::orderBy('sort_order')
            ->orderByDesc('start_year')
            ->get();

        $projects = Project::orderBy('sort_order')
            ->latest('created_at')
            ->get();

        $trainings = Training::orderBy('sort_order')
            ->latest('created_at')
            ->get();

        $totalExperience = Experience::totalExperience();

        return view('portfolio.index', compact(
            'profile',
            'skills',
            'experiences',
            'educations',
            'projects',
            'trainings',
            'totalExperience'
        ));
    }

    /**
     * Handle a contact form submission from the website.
     *
     * Includes a honeypot field ("website") to silently discard
     * automated spam submissions. Real users never fill it.
     */
    public function contact(Request $request): RedirectResponse|JsonResponse
    {
        // Honeypot: bots tend to fill every field. A filled honeypot
        // is dropped silently rather than rejected, so the bot can't
        // learn that it was detected.
        if (! empty($request->input('website'))) {
            return $this->contactSuccessResponse($request, true);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            $message = Message::create($validated);

            $this->sendNotificationEmail($message);

            return $this->contactSuccessResponse($request, false);
        } catch (\Throwable $e) {
            Log::error('Failed to store contact message: '.$e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while sending your message. Please try again later.',
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while sending your message. Please try again later.');
        }
    }

    /**
     * Send a notification email to the site owner about a new
     * contact message. If mail delivery fails the stored message
     * is still safe — the error is logged but never exposed to the
     * visitor, so the form always succeeds.
     */
    private function sendNotificationEmail(Message $message): void
    {
        try {
            $recipient = SiteSetting::current()?->notificationRecipient();

            if ($recipient) {
                Mail::to($recipient)->send(new ContactMessageReceived($message));
            }        } catch (\Throwable $e) {
            Log::error('Failed to send contact notification email: '.$e->getMessage());
        }
    }

    /**
     * Build the success response (JSON for AJAX, redirect otherwise).
     */
    private function contactSuccessResponse(Request $request, bool $honeypot): RedirectResponse|JsonResponse
    {
        $message = $honeypot
            ? 'Thank you! Your message has been sent successfully.'
            : 'Thank you! Your message has been sent successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
