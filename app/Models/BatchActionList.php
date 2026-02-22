<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchActionList extends Model
{
    protected $table = 'batch_action_lists';

    protected $fillable = [
        'name',
    ];

    public function batchActions(): HasMany
    {
        return $this->hasMany(BatchAction::class, 'action', 'name');
    }
}

