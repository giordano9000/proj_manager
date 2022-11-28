<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends AbstractRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|unique:tasks|max:255',
            'description' => 'required|string|max:255',
            'assignee' => 'required|uuid|exists:users,id',
            'difficulty' => 'required|integer|in:1,2,3,5,8,13,21',
            'priority' => ['required', new EnumValue( TaskPriority::class ) ]
        ];
    }
}
