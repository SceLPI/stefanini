<?php

namespace App\Entities;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use TypeError;

class ProjectEntity extends BaseEntity
{
    public int $id;
    public string $name;
    public string $due_date;
    public int $user_id;
    public ProjectStatusEnum $project_status_id;
    public ?string $reason;

    public function __construct(object $model = new Project())
    {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->due_date = $model->due_date;
        $this->user_id = $model->user_id;
        try {
            $this->project_status_id = $model->project_status_id;
        } catch (TypeError $e) {
            $this->project_status_id = ProjectStatusEnum::from($model->project_status_id);
        }
    }

}
