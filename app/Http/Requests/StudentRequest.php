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
}
