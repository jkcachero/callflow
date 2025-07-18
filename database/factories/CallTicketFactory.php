<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CallTicket>
 */
class CallTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'caller_name' => $this->faker->name(),
            'caller_number' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['active', 'completed', 'forwarded', 'escalated']),
            'assigned_user_id' => User::factory()->state(['role' => 'agent']),
        ];
    }
}
