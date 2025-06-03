<?php

namespace App\Models;

use App\Enums\ProjectStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = ['name', 'due_date', 'project_status_id', 'reason'];

    protected $casts = [
        'due_date' => 'date',
        'project_status_id' => ProjectStatusEnum::class,
    ];
}
