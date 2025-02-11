<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request): View|Factory|Application
    {
        $reservations = Reservation::query()
            ->with('event')
            ->when(
                $request->get('status') !== null,
                fn ($query) => $query->where('status', $request->get('status'))
            )
            ->latest()
            ->paginate(10);

        return view('reservations/index', compact('reservations'));
    }

    public function create(): View|Factory|Application
    {
        $events = Event::query()
            ->get();

        return view('reservations/create', compact('events'));
    }

    public function store(ReservationRequest $request): RedirectResponse
    {
        Reservation::query()
            ->create($request->validated());

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    public function edit(Reservation $reservation): View|Factory|Application
    {
        $events = Event::query()
            ->get();

        return view('reservations/edit', compact('reservation', 'events'));
    }

    public function update(ReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $reservation->update($request->validated());

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }

    public function show(Reservation $reservation): View|Factory|Application
    {
        $reservation->loadMissing('event');

        return view('reservations.show', compact('reservation'));
    }
}
