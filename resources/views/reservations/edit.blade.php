@section('content')
    <div class="container">
        <h1>Edit Reservation</h1>

        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">User Name:</label>
                <input type="text" name="user_name" class="form-control" value="{{ $reservation->user_name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ticket Quantity:</label>
                <input type="number" name="ticket_quantity" class="form-control" value="{{ $reservation->ticket_quantity }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="canceled" {{ $reservation->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Event:</label>
                <select name="event_id" class="form-control" required>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}" {{ $reservation->event_id == $event->id ? 'selected' : '' }}>
                            {{ $event->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Reservation</button>
        </form>
    </div>
@endsection
