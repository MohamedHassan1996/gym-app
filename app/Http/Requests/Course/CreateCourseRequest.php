<?php

namespace App\Http\Requests\Course;

use App\Enums\Course\CourseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;


class CreateCourseRequest extends FormRequest
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
            'startAt' => 'required',
            'endAt' => 'nullable',
            'description' => 'required',
            'classes' => 'required',
            'price' => 'required|numeric',
            'isActive' => ['required', new Enum(CourseStatus::class)],
            'trainerIds' => 'required',
            'sportCategoryId' => 'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
