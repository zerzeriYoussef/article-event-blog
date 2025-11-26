<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventParticipationAccepted extends Notification
{
    use Queueable;

    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $locale = app()->getLocale();
        $postName = $this->post->getTranslation('name', $locale) ?? $this->post->name;
        
        // Get slug for current locale
        $postSlug = $this->post->getTranslation('slug', $locale);
        if (!$postSlug && is_array($this->post->slug)) {
            $postSlug = $this->post->slug[$locale] ?? $this->post->slug['en'] ?? null;
        }
        if (!$postSlug) {
            $postSlug = is_string($this->post->slug) ? $this->post->slug : $this->post->id;
        }
        
        return (new MailMessage)
            ->subject(__('Your participation request has been accepted'))
            ->line(__('Your request to join the event ":event" has been accepted!', ['event' => $postName]))
            ->action(__('View Event'), route('posts.show', $postSlug))
            ->line(__('Thank you for your interest!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $locale = app()->getLocale();
        $postName = $this->post->getTranslation('name', $locale) ?? $this->post->name;
        
        // Get slug for current locale
        $postSlug = $this->post->getTranslation('slug', $locale);
        if (!$postSlug && is_array($this->post->slug)) {
            $postSlug = $this->post->slug[$locale] ?? $this->post->slug['en'] ?? null;
        }
        if (!$postSlug) {
            $postSlug = is_string($this->post->slug) ? $this->post->slug : $this->post->id;
        }
        
        return [
            'type' => 'event_participation_accepted',
            'message' => __('Your request to join the event ":event" has been accepted!', ['event' => $postName]),
            'post_id' => $this->post->id,
            'post_slug' => $postSlug,
            'post_name' => $postName,
        ];
    }
}

