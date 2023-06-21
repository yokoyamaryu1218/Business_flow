<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\JapaneseAndAlphaNumRule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employee_number' => 'required|numeric|unique:users,employee_number',
            'name' => ['required', 'string', 'max:255', new JapaneseAndAlphaNumRule],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*?[a-z])(?=.*?\d)[a-z\d]+$/i'], // 英数混合8文字以上
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ];
    }
}
