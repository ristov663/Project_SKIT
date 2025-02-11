@section('content')
    <div class="container">
        <h1>Create New Reservation</h1>

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">User Name:</label>
                <input type="text" name="user_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ticket Quantity:</label>
                <input type="number" name="ticket_quantity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Event:</label>
                <select name="event_id" class="form-control" required>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create Reservation</button>
        </form>
    </div>
@endsection
