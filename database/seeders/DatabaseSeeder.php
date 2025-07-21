<?php

namespace Database\Seeders;

use App\Models\CallLog;
use App\Models\CallTicket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'jkcachero@admin.com',
            'role' => 'admin'
        ]);

        CallTicket::factory(50)->create([
            'assigned_user_id' => $admin->id,
            'status' => 'active'
        ]);

        User::factory(20)->create()->each(function ($user) {
            for ($i = 0; $i < 50; $i++) {
                $callTicket = CallTicket::factory()->create([
                    'caller_name' => fake()->name(),
                    'caller_number' => fake()->numerify('+1##########'),
                    'status' => CallTicket::STATUS_OPTIONS[array_rand(CallTicket::STATUS_OPTIONS)],
                    'assigned_user_id' => $user->id,
                ]);

                CallLog::factory()->create([
                    'call_ticket_id' => $callTicket->id,
                    'user_id' => $user->id,
                    'log_type' => 'note',
                ]);
            }
        });
    }
}
