<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
    /** @use HasFactory<\Database\Factories\CallLogFactory> */
    use HasFactory;

    protected $fillable = [
        'call_ticket_id',
        'user_id',
        'note',
        'log_type',
    ];

    public function callTicket(): BelongsTo
    {
        return $this->belongsTo(CallTicket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
