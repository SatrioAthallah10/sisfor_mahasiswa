<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = is_object($student) ? $student->id : $student;

        return [
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students', 'nim')->ignore($studentId),
            ],
            'name' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
            'gpa' => 'required|numeric|min:0|max:4',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => __('The nim field is required.'),
            'nim.max' => __('The nim field must not be greater than :max characters.', ['max' => 20]),
            'nim.unique' => __('The nim has already been taken.'),
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name field must not be greater than :max characters.', ['max' => 100]),
            'prodi.required' => __('The prodi field is required.'),
            'prodi.max' => __('The prodi field must not be greater than :max characters.', ['max' => 100]),
            'gpa.required' => __('The gpa field is required.'),
            'gpa.numeric' => __('The gpa field must be a number.'),
            'gpa.min' => __('The gpa field must be at least :min.', ['min' => 0]),
            'gpa.max' => __('The gpa field must not be greater than :max.', ['max' => 4]),
            'photo.image' => __('The photo field must be an image.'),
            'photo.mimes' => __('The photo field must be a file of type: :values.', ['values' => 'jpg, jpeg, png']),
            'photo.max' => __('The photo field must not be greater than :max kilobytes.', ['max' => 2048]),
        ];
    }
}
