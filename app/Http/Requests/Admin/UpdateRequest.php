<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'name' => 'min:6|max:255',
            'email' => 'email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'min:8',
            'passwordConfirmation' => 'required_with:password|min:8|same:password',
        ];
    }
}
