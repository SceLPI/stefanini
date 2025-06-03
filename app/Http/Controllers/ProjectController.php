<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectPostFormRequest;
use App\Http\Requests\Project\ProjectPutFormRequest;
use App\Http\Services\ProjectService;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function __construct(private ProjectService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->list(request: $request));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json($this->service->get(user: $request->user(), id: $id));
    }

    public function store(ProjectPostFormRequest $request): JsonResponse
    {
        return response()->json($this->service->create(
            user: $request->user(),
            name: $request->name,
            dueDate: $request->due_date,
            projectStatusId: $request->project_status_id,
        ), 201);
    }

    public function update(ProjectPutFormRequest $request, Project $project): JsonResponse
    {
        return response()->json($this->service->update(
            user: $request->user(),
            project: $project,
            name: $request->name,
            dueDate: $request->due_date,
            projectStatusId: $request->project_status_id,
        ));
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        return response()->json($this->service->delete(user: $request->user(), project: $project), 204);
    }
}
