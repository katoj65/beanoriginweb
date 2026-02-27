<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenActivity extends Model
{
    protected $table = 'token_activities';

    protected $fillable = [
        'token_id',
        'activity',
    ];

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'token_id');
    }
}
