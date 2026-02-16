<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpCenter extends Model
{
    protected $table = 'help_centers';

    protected $fillable = [
        'category',
        'priority',
        'subject',
        'description',
        'preferred_channel',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status',
        'user_id',
        'cooperative_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }
}
