<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $table = 'blocks';
    public $timestamps = true;

    protected $fillable = [
        'batch_id',
        'block_index',
        'event_type',
        'current_hash',
        'previous_hash',
        'current_owner',
        'previous_owner',
        'event_data',
        'weight',
        'price',
    ];

    protected $casts = [
        'event_data' => 'array',
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function currentOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_owner');
    }

    public function previousOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'previous_owner');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(BlockPrice::class, 'block_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(BlockPurchase::class, 'block_id');
    }
}
