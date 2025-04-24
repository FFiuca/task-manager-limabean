<?php

namespace App\Models;

use App\Models\Master\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function epic(): BelongsTo{
        return $this->belongsTo(Epic::class, 'epic_id', 'id');
    }

    public function reportTo(): BelongsTo{
        return $this->belongsTo(User::class, 'report_to_user_id', 'id');
    }

    public function assignTo(): BelongsTo{
        return $this->belongsTo(User::class, 'assign_user_id', 'id');
    }

    public function createdBy(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function deletedBy(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function userWhoCommented(): BelongsToMany{
        return $this->belongsToMany(User::class, TaskComment::class,  'task_id', 'created_by');
    }

    public function taskComment(): HasMany{
        return $this->hasMany(TaskComment::class, 'id', 'task_id');
    }


}
