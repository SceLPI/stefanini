<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    public function test_01_01_find_list(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('v1.projects.index'));
        $response->assertStatus(200);
    }

    public function test_01_02_find_list_unauthenticated(): void
    {
        $response = $this->get(route('v1.projects.index'));
        $response->assertStatus(401);
    }

    public function test_02_01_fetch_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);
        //STORE ON REDIS TO CORRECT BEHAVIOR
        Redis::set('project:' . $project->user_id . ':' . $project->id, json_encode($project));

        $response = $this->actingAs($user)->get(route('v1.projects.show', [$project->id]));
        $response->assertStatus(200);
    }

    public function test_02_02_fetch_project_unauthenticated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);
        //STORE ON REDIS TO CORRECT BEHAVIOR
        Redis::set('project:' . $project->user_id . ':' . $project->id, json_encode($project));

        $response = $this->get(route('v1.projects.show', [$project->id]));
        $response->assertStatus(401);
    }

    public function test_02_03_fetch_non_existing_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);
        //STORE ON REDIS TO CORRECT BEHAVIOR
        Redis::set('project:' . $project->user_id . ':' . $project->id, json_encode($project));

        $response = $this->actingAs($user)->get(route('v1.projects.show', [$project->id + 1]));
        $response->assertStatus(404);
    }

    public function test_02_04_fetch_non_existing_project_unauthenticated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);
        //STORE ON REDIS TO CORRECT BEHAVIOR
        Redis::set('project:' . $project->user_id . ':' . $project->id, json_encode($project));

        $response = $this->get(route('v1.projects.show', [$project->id + 1]));
        $response->assertStatus(401);
    }

    public function test_02_05_fetch_other_user_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);
        Redis::set('project:' . $project->user_id . ':' . $project->id, json_encode($project));

        $me = User::factory()->create();

        $response = $this->actingAs($me)->get(route('v1.projects.show', [$project->id]));
        $response->assertStatus(404);
    }

    public function test_03_01_delete(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('v1.projects.destroy', [$project->id]));
        $response->assertStatus(204);
    }

    public function test_03_02_delete(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('v1.projects.destroy', [$project->id + 1]));
        $response->assertStatus(404);
    }

    public function test_03_03_delete_unauthenticated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->delete(route('v1.projects.destroy', [$project->id]));
        $response->assertStatus(401);
    }

    public function test_03_03_delete_non_existing_unauthenticated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->delete(route('v1.projects.destroy', [$project->id + 1]));
        $response->assertStatus(401);
    }

    public function test_03_03_delete_from_other_user(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $me = User::factory()->create();

        $response = $this->actingAs($me)->delete(route('v1.projects.destroy', [$project->id]));
        $response->assertStatus(status: 404);
    }

    public function test_04_01_create(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('v1.projects.store'), [
            "name" => fake()->name(),
            "due_date" => now()->addDays(fake()->numberBetween(10, 20)),
            "project_status_id" => fake()->numberBetween(1, 6),
        ]);

        $response->assertStatus(status: 201);
    }

    public function test_04_02_create_with_invalid_name(): void
    {
        $invalidNames = ['a', 'aa', 'aaa', 'aaaa'];
        $user = User::factory()->create();

        foreach ($invalidNames as $invalidName) {
            $response = $this->actingAs($user)->post(route('v1.projects.store'), [
                "name" => $invalidName,
                "due_date" => now()->addDays(fake()->numberBetween(10, 20)),
                "project_status_id" => fake()->numberBetween(1, 6),
            ]);

            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.name'
                ])
            );
        }
    }

    public function test_04_03_create_with_invalid_dates(): void
    {
        $invalidDates = [
            now()->subDays(3)->format('Y-m-d'),
            now()->subDays(2)->format('Y-m-d'),
            now()->subDays(1)->format('Y-m-d'),
            now()->format('Y-m-d'),
        ];
        $user = User::factory()->create();

        foreach ($invalidDates as $invalidDate) {
            $response = $this->actingAs($user)->post(route('v1.projects.store'), [
                "name" => fake()->name(),
                "due_date" => $invalidDate,
                "project_status_id" => fake()->numberBetween(1, 6),
            ]);

            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.due_date'
                ])
            );
        }
    }

    public function test_04_04_create_with_invalid_status(): void
    {
        $invalidStatouses = [
            -1,
            0,
        ];
        $user = User::factory()->create();

        foreach ($invalidStatouses as $invalidStatus) {
            $response = $this->actingAs($user)->post(route('v1.projects.store'), [
                "name" => fake()->name(),
                "due_date" => now()->addDay(),
                "project_status_id" => $invalidStatus,
            ]);

            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.project_status_id'
                ])
            );
        }
    }

    public function test_04_04_create_with_invalid_status_due_reason(): void
    {
        $invalidStatouses = [
            ProjectStatusEnum::ON_HOLD->value,
            ProjectStatusEnum::CANCELED->value,
        ];
        $user = User::factory()->create();

        foreach ($invalidStatouses as $invalidStatus) {
            $response = $this->actingAs($user)->post(route('v1.projects.store'), [
                "name" => fake()->name(),
                "due_date" => now()->addDay(),
                "project_status_id" => $invalidStatus,
            ]);

            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.reason'
                ])
            );
        }
    }

    public function test_04_05_create_with_all_invalid_status(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('v1.projects.store'), [
            "name" => fake()->name(),
            "due_date" => now()->addDay(),
            "project_status_id" => 20,
        ]);

        $response->assertStatus(422);
    }

    public function test_04_06_create_unauthenticated(): void
    {

        $response = $this->post(route('v1.projects.store'), [
            "name" => fake()->name(),
            "due_date" => now()->addDays(fake()->numberBetween(10, 20)),
            "project_status_id" => fake()->numberBetween(1, 6),
        ]);

        $response->assertStatus(status: 401);
    }

    public function test_05_01_edit(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->actingAs($user)->put(route('v1.projects.update', [$project->id]), [
            "name" => fake()->name(),
            "due_date" => now()->addDays(fake()->numberBetween(10, 20)),
            "project_status_id" => fake()->numberBetween(1, 6),
        ]);

        $response->assertStatus(status: 200);
    }

    public function test_05_02_edit_not_owner(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $me = User::factory()->create();

        $response = $this->actingAs($me)->put(route('v1.projects.update', [$project->id]), [
            "name" => fake()->name(),
            "due_date" => now()->addDays(fake()->numberBetween(10, 20)),
            "project_status_id" => fake()->numberBetween(1, 6),
        ]);

        $response->assertStatus(status: 404);
    }

    public function test_05_03_edit_with_error(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->actingAs($user)->put(route('v1.projects.update', [$project->id]), [
            "name" => "a",
            "due_date" => now()->format('Y-m-d'),
            "project_status_id" => 0,
        ]);

        $response->assertStatus(status: 422);
    }

}
