<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventParticipantController extends Controller
{
    /**
     * Request to join an event.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user already requested
        $existingRequest = EventParticipant::where('post_id', $post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRequest) {
            return back()->withErrors(['error' => 'You have already requested to join this event.']);
        }

        // Create new participation request
        EventParticipant::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your request to join this event has been submitted. The author will review it.');
    }

    /**
     * Cancel a participation request.
     */
    public function destroy(Post $post)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $participant = EventParticipant::where('post_id', $post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant) {
            $participant->delete();
            return back()->with('success', 'Your participation request has been cancelled.');
        }

        return back()->withErrors(['error' => 'Participation request not found.']);
    }

    /**
     * Accept a participation request (author only).
     */
    public function accept(Post $post, EventParticipant $participant)
    {
        // Check if current user is the author
        if (Auth::id() !== $post->author_id) {
            abort(403, 'Only the event author can accept requests.');
        }

        $participant->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Send notification to user
        $participant->user->notify(new \App\Notifications\EventParticipationAccepted($post));

        return back()->with('success', 'Participation request accepted.');
    }

    /**
     * Reject a participation request (author only).
     */
    public function reject(Post $post, EventParticipant $participant)
    {
        // Check if current user is the author
        if (Auth::id() !== $post->author_id) {
            abort(403, 'Only the event author can reject requests.');
        }

        $participant->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        // Send notification to user
        $participant->user->notify(new \App\Notifications\EventParticipationRejected($post));

        return back()->with('success', 'Participation request rejected.');
    }
}

