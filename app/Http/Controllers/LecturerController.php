<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LecturerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'dosen');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $lecturers = $query->paginate(10)->appends($request->query());

        return view('lecturers.index', compact('lecturers'));
    }

    public function create(): View
    {
        return view('lecturers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dosen',
        ]);

        return redirect()->route('lecturers.index')->with('success', __('Lecturer created successfully.'));
    }

    public function edit(string $id): View
    {
        $lecturer = User::where('role', 'dosen')->findOrFail($id);

        return view('lecturers.edit', compact('lecturer'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $lecturer = User::where('role', 'dosen')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($lecturer->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $lecturer->name = $validated['name'];
        $lecturer->email = $validated['email'];
        if (!empty($validated['password'])) {
            $lecturer->password = Hash::make($validated['password']);
        }
        $lecturer->save();

        return redirect()->route('lecturers.index')->with('success', __('Lecturer updated successfully.'));
    }

    public function destroy(string $id): RedirectResponse
    {
        $lecturer = User::where('role', 'dosen')->findOrFail($id);
        $lecturer->delete();

        return redirect()->route('lecturers.index')->with('success', __('Lecturer deleted successfully.'));
    }
}
