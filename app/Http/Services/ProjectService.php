<?php

namespace App\Http\Services;

use App\Entities\ProjectEntity;
use App\Http\Repositories\ProjectRepository;
use App\Http\Services\Contracts\ServiceCreateContract;
use App\Http\Services\Contracts\ServiceDeleteContract;
use App\Http\Services\Contracts\ServiceGetContract;
use App\Http\Services\Contracts\ServiceUpdateContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProjectService
{

    public function __construct(private ProjectRepository $repository)
    {
    }

    public function list(Request $request)
    {
        return $this->repository->allPaginated(request: $request);
    }

    public function get(User $user, int $id): ProjectEntity
    {
        return $this->repository->find(user: $user, id: $id);
    }

    public function create(
        User $user,
        string $name,
        string $dueDate,
        string $projectStatusId
    ): ProjectEntity {
        return $this->repository->create(
            user: $user,
            name: $name,
            dueDate: $dueDate,
            projectStatusId: $projectStatusId,
        );
    }

    public function update(
        User $user,
        Model $project,
        ?string $name,
        ?string $dueDate,
        ?string $projectStatusId
    ): ProjectEntity {
        if ($project->user_id != $user->id) {
            abort(404, "Resource not found");
        }
        return $this->repository->update(
            user: $user,
            project: $project,
            name: $name ?? $project->name,
            dueDate: $dueDate ?? $project->due_date,
            projectStatusId: $projectStatusId ?? $project->project_status_id,
        );
    }

    public function delete(User $user, Model $project): void
    {
        if ($project->user_id != $user->id) {
            abort(404, "Resource not found");
        }
        $this->repository->delete($project);
    }

}
