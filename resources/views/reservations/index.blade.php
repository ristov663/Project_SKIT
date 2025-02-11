@section('content')
    <div class="container">
        <h1>Reservations</h1>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary mb-3">Create New Reservation</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>User Name</th>
                <th>Tickets</th>
                <th>Status</th>
                <th>Event</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->user_name }}</td>
                    <td>{{ $reservation->ticket_quantity }}</td>
                    <td>{{ $reservation->status }}</td>
                    <td>{{ $reservation->event->name }}</td>
                    <td>
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
