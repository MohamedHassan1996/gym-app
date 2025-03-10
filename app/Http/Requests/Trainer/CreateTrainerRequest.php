<?php

namespace App\Http\Requests\Trainer;

use App\Enums\User\UserStatus;
use App\Enums\User\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;


class CreateTrainerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => [
                'required',
                'unique:users,email'
            ],
            'phone' => 'nullable',
            'address' => 'nullable',
            'status' => ['required', new Enum(UserStatus::class)],
            'password'=> [
                'required',
                'string',
                Password::min(8)
            ],
            'avatar' => ["sometimes", "nullable","image", "mimes:jpeg,jpg,png,gif", "max:2048"],
            'description' => 'nullable',
            'dateOfBirth' => 'nullable',
            'gender' => 'required',
            'role' => ['required', new Enum(UserType::class)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
