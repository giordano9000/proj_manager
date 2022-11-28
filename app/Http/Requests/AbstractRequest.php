<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class AbstractRequest extends FormRequest
{

    /**
     * Generic validation fail handler
     *
     * @param Validator $validator
     */
    protected function failedValidation( Validator $validator )
    {

        $response = [
            'message' => $validator->errors()->first(),
            'data' => $validator->errors()
        ];

        throw new HttpResponseException(response()->json( $response, 422 ));

    }

}
