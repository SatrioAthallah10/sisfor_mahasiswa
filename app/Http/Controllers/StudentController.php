<?php

namespace App\Http\Controllers;

/**
 * SETUP REQUIRED:
 * Run the following command in terminal to link storage:
 * php artisan storage:link
 */

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource with search and filter.
     */
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
        
        // Fetch distinct study programs for filter dropdown
        $prodis = Student::distinct()->pluck('prodi');

        return view('students.index', compact('students', 'prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Handle profile photo upload
        $photoPath = $this->handlePhotoUpload($request, null);
        $validated['photo_path'] = $photoPath;

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $student = Student::findOrFail($id);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $student = Student::findOrFail($id);

        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, string $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $validated = $request->validated();

        // Handle profile photo upload (pass current photo_path as oldPath)
        $photoPath = $this->handlePhotoUpload($request, $student->photo_path);
        $validated['photo_path'] = $photoPath;

        $student->update($validated);

        return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    /**
     * Handle the photo upload logic.
     */
    private function handlePhotoUpload(Request $request, ?string $oldPath): ?string
    {
        // 1. If no file in request, return $oldPath unchanged
        if (!$request->hasFile('photo')) {
            return $oldPath;
        }

        // 2. Validate already handled by StudentRequest
        // 3. Generate UUID-based filename to avoid collision
        $filename = Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();

        // 4. Store file to storage/app/public/photos
        $request->file('photo')->storeAs('photos', $filename, 'public');

        // 5. If updating and $oldPath exists, delete old file from disk
        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        // 6. Return 'photos/'.$filename
        return 'photos/' . $filename;
    }
}
