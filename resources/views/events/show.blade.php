@section('content')
    <div class="container">
        <h1>{{ $event->name }}</h1>
        <p><strong>Description:</strong> {{ $event->description }}</p>
        <p><strong>Location:</strong> {{ $event->location }}</p>
        <p><strong>Date:</strong> {{ $event->event_date }}</p>
        <p><strong>Capacity:</strong> {{ $event->capacity }}</p>

        <h2>Reservations for this Event</h2>
        <table class="table">
            <thead>
            <tr>
                <th>User Name</th>
                <th>Tickets</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($event->reservations as $reservation)
                <tr>
                    <td>{{ $reservation->user_name }}</td>
                    <td>{{ $reservation->ticket_quantity }}</td>
                    <td>{{ $reservation->status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
    </div>
@endsection
