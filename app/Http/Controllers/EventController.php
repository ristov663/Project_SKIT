<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): View|Factory|Application
    {
        $events = Event::query()
            ->when($request->has('search'),
                fn ($query) => $query->where('name', 'like', '%'.$request->get('search').'%'))
            ->latest()
            ->paginate(10);

        return view('events/index', compact('events'));
    }

    public function create(): View|Factory|Application
    {
        return view('events/create');
    }

    public function store(EventRequest $request): RedirectResponse
    {
        Event::query()
            ->create($request->validated());

        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event): View|Factory|Application
    {
        return view('events/edit', compact('event'));
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        return redirect()
            ->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function show(Event $event): View|Factory|Application
    {
        $event->loadMissing('reservations');

        return view('events/show', compact('event'));
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->reservations()->delete();
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
