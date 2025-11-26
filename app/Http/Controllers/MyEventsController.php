<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\EventParticipant;
use App\Http\Resources\PostResource;

class MyEventsController extends Controller
{
    /**
     * Display the user's event participations.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $participations = EventParticipant::where('user_id', $user->id)
            ->with(['post', 'post.category', 'post.author'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn($p) => $p->post !== null); // Filter out deleted posts

        // Group by status
        $pending = $participations->where('status', 'pending')
            ->filter(fn($p) => $p->post !== null)
            ->map(function ($participation) {
                try {
                    return [
                        'id' => $participation->id,
                        'post' => (new PostResource($participation->post))->resolve(),
                        'status' => $participation->status,
                        'message' => $participation->message,
                        'created_at' => $participation->created_at?->toDateTimeString(),
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing participation: ' . $e->getMessage());
                    return null;
                }
            })
            ->filter()
            ->values();

        $accepted = $participations->where('status', 'accepted')
            ->filter(fn($p) => $p->post !== null)
            ->map(function ($participation) {
                try {
                    return [
                        'id' => $participation->id,
                        'post' => (new PostResource($participation->post))->resolve(),
                        'status' => $participation->status,
                        'message' => $participation->message,
                        'created_at' => $participation->created_at?->toDateTimeString(),
                        'responded_at' => $participation->responded_at?->toDateTimeString(),
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing participation: ' . $e->getMessage());
                    return null;
                }
            })
            ->filter()
            ->values();

        $rejected = $participations->where('status', 'rejected')
            ->filter(fn($p) => $p->post !== null)
            ->map(function ($participation) {
                try {
                    return [
                        'id' => $participation->id,
                        'post' => (new PostResource($participation->post))->resolve(),
                        'status' => $participation->status,
                        'message' => $participation->message,
                        'created_at' => $participation->created_at?->toDateTimeString(),
                        'responded_at' => $participation->responded_at?->toDateTimeString(),
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing participation: ' . $e->getMessage());
                    return null;
                }
            })
            ->filter()
            ->values();

        return Inertia::render('MyEvents', [
            'pending' => $pending->toArray(),
            'accepted' => $accepted->toArray(),
            'rejected' => $rejected->toArray(),
        ]);
    }
}

