<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallTicket extends Model
{
    use HasFactory;

    public const STATUS_OPTIONS = [
        'active',
        'completed',
        'forwarded',
        'escalated'
    ];

    protected $fillable = [
        'caller_name',
        'caller_number',
        'status',
        'assigned_user_id',
    ];

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
