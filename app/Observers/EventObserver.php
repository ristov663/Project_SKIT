<?php

namespace App\Observers;

use App\Models\Event;
use Illuminate\Support\Str;

class EventObserver
{
    /**
     * Handle the Event "creating" event.
     */
    public function creating(Event $event): void
    {
        $event->slug = Str::slug($event->name);
    }

    /**
     * Handle the Event "deleting" event.
     */
    public function deleting(Event $event): void
    {
        $event->reservations()->delete();
    }
}
