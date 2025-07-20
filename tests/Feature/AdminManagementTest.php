<?php

use App\Models\User;
use Illuminate\Http\Response;

use function Pest\Laravel\actingAs;

test('allows admin to create a new agent', function () {
    $this->withoutExceptionHandling();

    $admin = User::factory()->admin()->create([
        'email' => 'admin@example.com',
    ]);

    actingAs($admin)
        ->post(route('users.store'), [
            'name' => 'Agent Orange',
            'email' => 'agent@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'agent'
        ])
        ->assertStatus(Response::HTTP_CREATED);
});

it('allows admin to update a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->agent()->create();

    actingAs($admin)
        ->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'role' => 'supervisor',
        ])
        ->assertStatus(Response::HTTP_OK);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'role' => 'supervisor',
    ]);
});

it('prevents non-admin from updating a user', function () {
    $agent = User::factory()->agent()->create();
    $user = User::factory()->agent()->create();

    actingAs($agent)
        ->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'supervisor',
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows admin to delete a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->agent()->create();

    actingAs($admin)
        ->delete(route('users.destroy', $user))
        ->assertStatus(Response::HTTP_OK);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('prevents non-admin from deleting a user', function () {
    $agent = User::factory()->agent()->create();
    $user = User::factory()->agent()->create();

    actingAs($agent)
        ->delete(route('users.destroy', $user))
        ->assertStatus(Response::HTTP_FORBIDDEN);
});
