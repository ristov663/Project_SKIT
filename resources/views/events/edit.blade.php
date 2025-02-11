@section('content')
    <div class="container">
        <h1>Edit Event</h1>

        <form action="{{ route('events.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $event->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" required>{{ $event->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location:</label>
                <input type="text" name="location" class="form-control" value="{{ $event->location }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" name="event_date" class="form-control" value="{{ $event->event_date }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Capacity:</label>
                <input type="number" name="capacity" class="form-control" value="{{ $event->capacity }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
@endsection
