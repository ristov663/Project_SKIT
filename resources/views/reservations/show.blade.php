@section('content')
    <div class="container">
        <h1>Reservation Details</h1>

        <p><strong>User Name:</strong> {{ $reservation->user_name }}</p>
        <p><strong>Ticket Quantity:</strong> {{ $reservation->ticket_quantity }}</p>
        <p><strong>Status:</strong> {{ ucfirst($reservation->status) }}</p>
        <p><strong>Event:</strong> <a href="{{ route('events.show', $reservation->event->id) }}">{{ $reservation->event->name }}</a></p>

        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Back to Reservations</a>
    </div>
@endsection
