<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('call_tickets', function (Blueprint $table) {
            $table->id();
            $table->text('caller_name');
            $table->text('caller_number');
            $table->enum('status', ['active', 'completed', 'forwarded', 'escalated']);
            $table->foreignId('assigned_user_id');
            $table->timestamps();

            $table->foreign('assigned_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_tickets');
    }
};
