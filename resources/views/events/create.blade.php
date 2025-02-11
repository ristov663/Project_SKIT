@section('content')
    <div class="container">
        <h1>Create New Event</h1>

        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location:</label>
                <input type="text" name="location" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" name="event_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Capacity:</label>
                <input type="number" name="capacity" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Create Event</button>
        </form>
    </div>
@endsection
