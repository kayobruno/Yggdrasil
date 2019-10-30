<?php

namespace App\Http\Requests;

use App\Traits\Rest\ResponseHelpers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRequest extends FormRequest
{
    use ResponseHelpers;

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException($this->createApiResponse(
            ['errors' => $errors],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
