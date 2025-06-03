<?php

namespace App\Http\Repositories;

use App\Entities\ProjectEntity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;

class ProjectRepository
{

    public function allPaginated(Request $request): LengthAwarePaginator
    {
        $projects = Project::where('user_id', $request->user()->id)->get();
        return ProjectEntity::paginatedEntities($projects);
    }

    public function find(User $user, int $id): ProjectEntity
    {
        $project = Redis::get('project:' . $user->id . ':' . $id);
        if (!$project) {
            abort(404, "Resource not found");
        }
        return new ProjectEntity(model: json_decode($project));
    }

    public function create(
        User $user,
        string $name,
        string $dueDate,
        string $projectStatusId,
        ?Model $project = new Project(),
    ): ProjectEntity {
        $project->name = $name;
        $project->due_date = $dueDate;
        $project->project_status_id = $projectStatusId;
        $project->user_id ??= $user->id;
        $project->save();

        Redis::set('project:' . $project->user_id . ':' . $project->id, json_encode($project));

        return new ProjectEntity(model: $project);
    }

    public function update(
        User $user,
        Model $project,
        string $name,
        string $dueDate,
        string $projectStatusId,
    ): ProjectEntity {
        return $this->create(
            user: $user,
            name: $name,
            dueDate: $dueDate,
            projectStatusId: $projectStatusId,
            project: $project,
        );
    }

    public function delete(Model $project): void
    {
        $project->delete();
        Redis::del('project:' . $project->user_id . ':' . $project->id);
    }
}
