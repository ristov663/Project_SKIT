<?php

use App\Actions\CancelReservationAction;
use App\Actions\ConfirmReservationAction;
use App\Enums\ReservationStatusEnum;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

// Тестира дали постои табелата "events" и дали ги содржи очекуваните полиња
it('verifies events table structure', function () {

    expect(Schema::hasTable('events'))->toBeTrue();

    $expectedColumns = [
        'id', 'name', 'slug', 'description', 'location',
        'event_date', 'capacity', 'created_at', 'updated_at',
    ];

    foreach ($expectedColumns as $column) {
        expect(Schema::hasColumn('events', $column))->toBeTrue();
    }
});

// Тестира дали постои табелата "reservations" и дали ги содржи очекуваните полиња
it('verifies reservations table structure', function () {

    expect(Schema::hasTable('reservations'))->toBeTrue();

    $expectedColumns = [
        'id', 'event_id', 'user_name', 'ticket_quantity',
        'status', 'created_at', 'updated_at',
    ];

    foreach ($expectedColumns as $column) {
        expect(Schema::hasColumn('reservations', $column))->toBeTrue();
    }
});

// Тестира успешно креирање на нов настан
it('allows creating a valid event', function () {

    $eventData = [
        'name' => 'Seniors Meetup',
        'description' => 'Seniors meetup for engineers.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(30)->toDateString(),
        'capacity' => 50,
    ];
    $response = $this->postJson('/api/events', $eventData);
    $response->assertStatus(201);
    $this->assertDatabaseHas('events', $eventData);
});

// Тестира дали API враќа грешка при креирање на настан без задолжителните полиња (capacity and slug)
it('rejects event creation without required fields', function () {

    $response = $this->postJson('/api/events', []);

    $response->assertStatus(422)->assertJsonValidationErrors([
        'name', 'description', 'location', 'event_date'
    ]);
});

// Тестира успешна промена на постоечки настан со валидни податоци
it('updates an existing event', function () {

    $event = Event::factory()->create([
        'name' => 'Initial Event',
        'description' => 'Initial description for the event.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(30),
        'capacity' => 100,
    ]);

    $updatedEvent = [
        'name' => 'Updated Event',
        'description' => 'Initial description for the event.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(15)->toDateString(),
        'capacity' => 80,
    ];

    $response = $this->putJson("/api/events/{$event->id}", $updatedEvent);

    $response->assertStatus(200);
    $this->assertDatabaseHas('events', $updatedEvent);
});

// Тестира неуспешна промена на постоечки настан со негативен капацитет
it('fails to update an event with negative capacity', function () {

    $event = Event::factory()->create([
        'name' => 'Initial Event',
        'description' => 'Initial description for the event.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(30),
        'capacity' => 100,
    ]);

    $updatedEvent = [
        'name' => 'Updated Event',
        'description' => 'Initial description for the event.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(15)->toDateString(),
        'capacity' => -10,
    ];

    $response = $this->putJson("/api/events/{$event->id}", $updatedEvent);

    $response->assertStatus(422);
    $this->assertDatabaseMissing('events', $updatedEvent);
});

// Тестира успешно пребарување на настани по име или локација
it('search events by name or location', function () {

    Event::factory()->create([
        'name' => 'Seniors Meetup',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(30)->toDateString(),
        'capacity' => 50,
    ]);

    Event::factory()->create([
        'name' => 'Juniors Meetup',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(20)->toDateString(),
        'capacity' => 100,
    ]);

    $response = $this->getJson('/api/events?search=Seniors');

    $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->has('data.0', fn ($json) => $json->where('name', 'Seniors Meetup')
                ->where('slug', 'seniors-meetup')
                ->where('location', 'Skopje, Macedonia')
                ->where('capacity', 50)
                ->etc()
            )
            ->hasAll(['links', 'meta'])
        );

    $response = $this->getJson('/api/events');

    $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 2)
            ->hasAll(['links', 'meta'])
        );
});

// Тестира неуспешно додавање на резервација доколку ticket quantity го надминува capacity
it('fails to create a reservation if ticket quantity exceeds capacity', function () {

    $event = Event::factory()->create(['capacity' => 10]);

    $data = [
        'event_id' => $event->id,
        'user_name' => 'Bojan Doe',
        'ticket_quantity' => 30,
    ];

    $response = $this->postJson('/api/reservations', $data);

    $response->assertStatus(400);
    $this->assertDatabaseMissing('reservations', $data);
});

// Тестира дали класата ConfirmReservationAction постои
it('checks if ConfirmReservationAction class exists', function () {

    $this->assertTrue(class_exists(ConfirmReservationAction::class));
});

// Тестира дали класата CancelReservationAction постои
it('checks if CancelReservationAction class exists', function () {

    $this->assertTrue(class_exists(CancelReservationAction::class));
});

// Тестира бришење на настан
it('deletes an event', function () {

    $event = Event::factory()->create();

    Reservation::factory()->create([
        'event_id' => $event->id,
        'status' => ReservationStatusEnum::CONFIRMED,
    ]);

    $response = $this->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

// Тестира неуспешна промена на настан кога додаваме минат датум
it('fails to update an event with date in the past', function () {

    $event = Event::factory()->create([
        'name' => 'Seniors Meetup',
        'description' => 'Senior meetup description for engineers.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(10)->toDateString(),
        'capacity' => 50,
    ]);

    $updatedEvent = [
        'name' => 'Seniors Meetup',
        'description' => 'Senior meetup description for engineers.',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->subDays(15)->toDateString(),
        'capacity' => 50,
    ];

    $response = $this->putJson("/api/events/{$event->id}", $updatedEvent);

    $response->assertStatus(422);
    $this->assertDatabaseMissing('events', $updatedEvent);
});

// Тестира неуспешно додавање на резервација ако настанот не постои
it('fails to create a reservation if event_id does not exist', function () {

    $this->assertDatabaseMissing('events', ['id' => 9999]);

    $data = [
        'event_id' => 9999,
        'user_name' => 'John Doe',
        'ticket_quantity' => 5,
    ];

    $response = $this->postJson('/api/reservations', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['event_id']);

    $this->assertDatabaseMissing('reservations', [
        'user_name' => 'John Doe',
        'ticket_quantity' => 5,
    ]);
});

// Тестира преглед на конкретен настан со неговите резервации
it('shows a single event with its reservations', function () {
    $event = Event::factory()->create([
        'name' => 'Seniors Meetup',
        'location' => 'Skopje, Macedonia',
        'event_date' => now()->addDays(30)->toDateString(),
        'capacity' => 500,
    ]);

    $reservations = Reservation::factory()->count(2)->create([
        'event_id' => $event->id,
        'user_name' => 'John Doe',
        'ticket_quantity' => 5,
    ]);

    $response = $this->getJson("/api/events/{$event->id}");

    $response->assertStatus(200);

    $response->assertJson([
        'data' => [
            'name' => 'Seniors Meetup',
            'location' => 'Skopje, Macedonia',
            'capacity' => 500,
            'reservations' => $reservations->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'user_name' => $reservation->user_name,
                    'ticket_quantity' => $reservation->ticket_quantity,
                ];
            })->toArray(),
        ],
    ]);
});
