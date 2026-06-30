<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with('lecturer');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('code', 'like', '%'.$search.'%');
            });
        }

        $courses = $query->paginate(10)->appends($request->query());

        return view('courses.index', compact('courses'));
    }

    public function create(): View
    {
        $lecturers = User::where('role', 'dosen')->orderBy('name')->get();

        return view('courses.create', compact('lecturers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'lecturer_id' => 'nullable|exists:users,id',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', __('Course created successfully.'));
    }

    public function edit(string $id): View
    {
        $course = Course::findOrFail($id);
        $lecturers = User::where('role', 'dosen')->orderBy('name')->get();

        return view('courses.edit', compact('course', 'lecturers'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'code')->ignore($course->id),
            ],
            'lecturer_id' => 'nullable|exists:users,id',
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')->with('success', __('Course updated successfully.'));
    }

    public function destroy(string $id): RedirectResponse
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', __('Course deleted successfully.'));
    }
}
