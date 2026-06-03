<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('nim', 'like', '%'.$search.'%');
            });
        }

        if ($prodi = $request->input('prodi')) {
            $query->where('prodi', $prodi);
        }

        if ($gpaMin = $request->input('gpa_min')) {
            $query->where('gpa', '>=', $gpaMin);
        }

        if ($gpaMax = $request->input('gpa_max')) {
            $query->where('gpa', '<=', $gpaMax);
        }

        $students = $query->paginate(10)->appends($request->query());
        
        $prodis = Student::distinct()->pluck('prodi');

        return view('students.index', compact('students', 'prodis'));
    }

    public function create(): View
    {
        return view('students.create');
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        $photoPath = $this->handlePhotoUpload($request, null);
        $validated['photo_path'] = $photoPath;

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(string $id): View
    {
        $student = Student::findOrFail($id);

        return view('students.show', compact('student'));
    }

    public function edit(string $id): View
    {
        $student = Student::findOrFail($id);

        return view('students.edit', compact('student'));
    }

    public function update(StudentRequest $request, string $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $validated = $request->validated();

        $photoPath = $this->handlePhotoUpload($request, $student->photo_path);
        $validated['photo_path'] = $photoPath;

        $student->update($validated);

        return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    private function handlePhotoUpload(Request $request, ?string $oldPath): ?string
    {
        if (!$request->hasFile('photo')) {
            return $oldPath;
        }

        $filename = Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();

        $request->file('photo')->storeAs('photos', $filename, 'public');

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return 'photos/' . $filename;
    }
}
