<?php

use App\Models\User;
use App\Models\CallTicket;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->agent = User::factory()->create(['role' => 'agent']);
    $this->supervisor = User::factory()->create(['role' => 'supervisor']);
    $this->admin = User::factory()->create(['role' => 'admin']);

    $this->agentTickets = CallTicket::factory(3)->create(['assigned_user_id' => $this->agent->id]);
    $this->otherTickets = CallTicket::factory(2)->create();
});

it('allows agent to see only their assigned call tickets', function () {
    $response = $this->actingAs($this->agent)->get(route('call-tickets.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertInertia(
            fn($page) =>
            $page->component('CallTicket/Index')
                ->where(
                    'callTickets.data',
                    fn($tickets) => collect($tickets)->every(fn($ticket) => $ticket['assigned_user_id'] === $this->agent->id)
                )
        );
});

it('allows supervisor to see all call tickets', function () {
    $response = $this->actingAs($this->supervisor)->get(route('call-tickets.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertInertia(
            fn($page) =>
            $page->component('CallTicket/Index')
                ->where(
                    'callTickets.data',
                    fn($tickets) => collect($tickets)->contains(fn($ticket) => in_array($ticket['id'], $this->agentTickets->pluck('id')->toArray()))
                        && collect($tickets)->contains(fn($ticket) => in_array($ticket['id'], $this->otherTickets->pluck('id')->toArray()))
                )
        );
});

it('allows admin to see all call tickets', function () {
    $response = $this->actingAs($this->admin)->get(route('call-tickets.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertInertia(
            fn($page) =>
            $page->component('CallTicket/Index')
                ->where(
                    'callTickets.data',
                    fn($tickets) => collect($tickets)->contains(fn($ticket) => $ticket['id'] === $this->agentTickets->first()->id)
                        && collect($tickets)->contains(fn($ticket) => $ticket['id'] === $this->otherTickets->first()->id)
                )
        );
});

it('denies guests access to call tickets page', function () {
    $this->get(route('call-tickets.index'))->assertRedirect(route('login'));
});
