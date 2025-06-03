<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectPutFormRequest extends ProjectPostFormRequest
{

    public function rules(): array
    {
        $rules = parent::rules();

        $rules['name'] = 'min:5';
        $rules['due_date'] = 'date|after:today';
        $rules['project_status_id'] = [Rule::in(ProjectStatusEnum::values())];

        return $rules;
    }

}
