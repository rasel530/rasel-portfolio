<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Admin dashboard with overview statistics.
     */
    public function dashboard(): View
    {
        $unreadMessages = Message::where('is_read', false)->count();
        $totalMessages = Message::count();
        $projectsCount = Project::count();
        $skillsCount = Skill::count();
        $experiencesCount = Experience::count();
        $educationsCount = Education::count();
        $recentMessages = Message::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'unreadMessages',
            'totalMessages',
            'projectsCount',
            'skillsCount',
            'experiencesCount',
            'educationsCount',
            'recentMessages'
        ));
    }

    /**
     * List all contact messages.
     */
    public function messages(): View
    {
        $messages = Message::latest()->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show a single message and mark it as read.
     */
    public function showMessage(int $id): View
    {
        $message = Message::findOrFail($id);

        if (! $message->is_read) {
            $message->is_read = true;
            $message->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Toggle the read status of a message.
     */
    public function markRead(int $id): RedirectResponse
    {
        $message = Message::findOrFail($id);

        $message->is_read = ! $message->is_read;
        $message->save();

        return redirect()
            ->back()
            ->with('success', 'Message status updated successfully.');
    }

    /**
     * Delete a contact message.
     */
    public function destroyMessage(int $id): RedirectResponse
    {
        $message = Message::findOrFail($id);

        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
