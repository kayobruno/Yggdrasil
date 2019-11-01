<?php

namespace App\Http\Requests\Filter;

use App\Http\Requests\BaseRequest;

class FilterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orderBy' => 'string|max:30',
            'sort' => 'string|in:ASC,DESC',
            'perPage' => 'integer',
            'page' => 'integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['orderBy' => $this->orderBy ?? 'id']);
        $this->merge(['sort' => $this->sort ?? 'DESC']);
        $this->merge(['perPage' => $this->perPage ?? 10]);
        $this->merge(['page' => $this->page ?? 1]);
    }
}
