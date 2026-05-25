<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the student parameter from the route.
        // It could be an ID string or a Student model instance depending on route binding.
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
}
