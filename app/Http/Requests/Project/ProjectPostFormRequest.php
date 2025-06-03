<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectPostFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|min:5',
            'due_date' => 'required|date|after:today',
            'project_status_id' => ['required', Rule::in(ProjectStatusEnum::values())],
        ];

        $statusWithReason = [
            ProjectStatusEnum::CANCELED->value,
            ProjectStatusEnum::ON_HOLD->value,
        ];

        if (in_array($this->project_status_id, $statusWithReason)) {
            $rules['reason'] = ['required', 'string', 'min:5'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name' => __('project.invalid_name'),
            'due_date' => __('project.invalid_due_date'),
            'project_status_id' => __('project.invalid_status_id'),
            'reason' => __('project.invalid_reason')
        ];
    }
}
