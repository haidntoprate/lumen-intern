<?php

namespace App\Http\Requests\User;

use Anik\Form\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'avatar' => 'nullable|image',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'status' => 'required',
            'lock_time' => 'nullable',
        ];
    }
}
