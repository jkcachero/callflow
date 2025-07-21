<?php

namespace Database\Factories;

use App\Models\CallTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CallLog>
 */
class CallLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'call_ticket_id' => CallTicket::factory(),
            'user_id' => User::factory()->state(['role' => 'agent']),
            'note' => $this->faker->sentence(),
            'log_type' => $this->faker->randomElement(['note', 'status_change']),
        ];
    }
}
