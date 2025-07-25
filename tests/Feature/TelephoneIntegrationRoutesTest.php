<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->supervisor = User::factory()->create([
        'role' => 'supervisor',
    ]);

    $this->agent = User::factory()->create([
        'role' => 'agent',
    ]);
});

it('can view telephone routes', function () {
    $this->actingAs($this->admin)
        ->get('/telephone')
        ->assertStatus(Response::HTTP_OK);

    $this->actingAs($this->admin)
        ->get('/telephone/3cx')
        ->assertStatus(Response::HTTP_OK);

    $this->actingAs($this->admin)
        ->get('/telephone/twilio')
        ->assertStatus(Response::HTTP_OK);
});

it('can view telephone routes for supervisor', function () {
    $this->actingAs($this->supervisor)
        ->get('/telephone')
        ->assertStatus(Response::HTTP_OK);

    $this->actingAs($this->supervisor)
        ->get('/telephone/3cx')
        ->assertStatus(Response::HTTP_OK);

    $this->actingAs($this->supervisor)
        ->get('/telephone/twilio')
        ->assertStatus(Response::HTTP_OK);
});

it('cannot view telephone routes for agent', function () {
    $this->actingAs($this->agent)
        ->get('/telephone')
        ->assertStatus(Response::HTTP_FORBIDDEN);

    $this->actingAs($this->agent)
        ->get('/telephone/3cx')
        ->assertStatus(Response::HTTP_FORBIDDEN);

    $this->actingAs($this->agent)
        ->get('/telephone/twilio')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});
