<?php

namespace Tests\Unit;

use App\Enums\ProjectStatusEnum;
use App\Http\Repositories\ProjectRepository;
use App\Http\Services\ProjectService;
use App\Models\Project;
use Tests\TestCase;
use App\Models\User;

class ProjectUnitTest extends TestCase
{

    private $service;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->service = new ProjectService(repository: new ProjectRepository());
    }

    public function test_project_creation()
    {
        $user = User::factory()->create();
        $project = Project::factory()->make([
            "user_id" => $user->id
        ]);

        $projectEntity = $this->service->create(
            user: $user,
            name: $project->name,
            dueDate: $project->due_date,
            projectStatusId: $project->project_status_id->value,
        );

        $this->assertTrue(!empty($projectEntity->id));
        $this->assertTrue($projectEntity->name == $project->name);
        $this->assertTrue($projectEntity->user_id == $user->id);
        $this->assertTrue($projectEntity->project_status_id == $project->project_status_id);
        $this->assertTrue($projectEntity->due_date == $project->due_date);
    }

    public function test_project_update()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $newName = "New Test Name";
        $newDate = now()->addDays(5)->startOfDay()->format('Y-m-d H:i:s');
        $newStatus = ProjectStatusEnum::FINISHED;

        $projectEntity = $this->service->update(
            user: $user,
            project: $project,
            name: $newName,
            dueDate: $newDate,
            projectStatusId: $newStatus->value,
        );

        $this->assertTrue($projectEntity->name == $newName);
        $this->assertTrue($projectEntity->project_status_id == $newStatus);
        $this->assertTrue($projectEntity->due_date == $newDate);
    }

    public function test_deletion()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $oldId = $project->id;

        $this->service->delete(user: $user, project: $project);

        $this->assertDatabaseMissing('projects', ['id' => $oldId]);
    }
}
