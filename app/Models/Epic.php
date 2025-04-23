<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Epic extends Model
{
    /** @use HasFactory<\Database\Factories\EpicFactory> */
    use HasFactory;

    protected $guarded = ['id'];


    public function tasks(): HasMany{
        return $this->hasMany(Task::class, 'epic_id', 'id');
    }

    public function createdBy(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
