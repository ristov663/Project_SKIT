<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatusEnum;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventShowResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->get('search');

        $events = Event::query()
            ->where('name', 'like', '%'.$search.'%')
            ->orWhere('location', 'like', '%'.$search.'%')
            ->paginate();

        return EventResource::collection($events);
    }

    public function store(EventRequest $request): EventResource
    {
        $event = Event::query()
            ->create($request->validated());

        return EventResource::make($event);
    }

    public function update(Event $event, EventRequest $request): EventResource
    {
        $event->update($request->validated());

        return EventResource::make($event);
    }

    public function destroy(Event $event): JsonResponse
    {

        abort_if($event->reservations()
                ->where('status', ReservationStatusEnum::CONFIRMED)
                ->count() > 0,
            400);

        $event->delete();

        return response()->json();
    }

    public function show(Event $event): EventShowResource
    {
        $event->loadMissing('reservations');

        return EventShowResource::make($event);
    }
}
