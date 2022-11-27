<?php

namespace App\Http\Requests;

use App\Enums\ProjectSort;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class IndexProjectRequest extends AbstractRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page' => 'required|integer',
            'perPage' => 'required|integer',
            'sortBy' => [ new EnumValue( ProjectSort::class ) ],
            'withClosed' => 'string',
            'onlyClosed' => 'string|exclude_with:withClosed'
        ];
    }
}
