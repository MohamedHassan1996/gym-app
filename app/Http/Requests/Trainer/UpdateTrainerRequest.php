<?php

namespace App\Http\Requests\Trainer;

use App\Enums\User\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UpdateTrainerRequest extends FormRequest
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
            'userId' => 'required',
            'trainerId' => 'required',
            'name' => 'required',
            //'username'=> ['required', "unique:users,username,{$this->userId}"],
            'email'=> ['required', "unique:users,email,{$this->userId}"],
            'phone' => '',
            'address' => '',
            'status' => 'required',
            'password'=> [
                'sometimes',
                'nullable',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            'avatar' => ["sometimes", "nullable","image", "mimes:jpeg,jpg,png,gif", "max:2048"],
            'description' => 'required',
            'dateOfBirth' => 'required',
            'gender' => 'required',
            'sportCategoryIds' => 'required',
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
