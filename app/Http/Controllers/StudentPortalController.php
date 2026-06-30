<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $assignments = Assignment::orderByDesc('created_at')->limit(5)->get();
        $openSessions = AttendanceSession::where(function($q){
            $q->whereNull('open_at')->orWhere('open_at', '<=', now());
        })->where(function($q){
            $q->whereNull('close_at')->orWhere('close_at', '>=', now());
        })->get();

        $attendanceRecords = AttendanceRecord::where('user_id', auth()->id())->get()->keyBy('attendance_session_id');

        return view('student_portal.dashboard', compact('assignments', 'openSessions', 'attendanceRecords'));
    }

    public function assignmentsIndex()
    {
        $assignments = Assignment::orderByDesc('created_at')->paginate(10);

        return view('student_portal.assignments.index', compact('assignments'));
    }

    public function assignmentsShow($id)
    {
        $assignment = Assignment::findOrFail($id);
        $submission = Submission::where('assignment_id', $assignment->id)->where('user_id', auth()->id())->first();

        return view('student_portal.assignments.show', compact('assignment', 'submission'));
    }

    public function downloadQuestion($id)
    {
        $assignment = Assignment::findOrFail($id);

        if (! $assignment->question_file) {
            return redirect()->back()->with('error', __('No question file available.'));
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($assignment->question_file)) {
            return redirect()->back()->with('error', __('Question file not found.'));
        }

        return $disk->download($assignment->question_file);
    }

    public function submitAssignment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'file' => 'required|file|max:10240',
            'notes' => 'nullable|string|max:2000',
        ]);

        try {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('submissions', $filename, 'public');

            $submission = Submission::updateOrCreate(
                ['assignment_id' => $assignment->id, 'user_id' => auth()->id()],
                [
                    'file_path' => $path,
                    'notes' => $request->input('notes'),
                    'submitted_at' => now(),
                ]
            );

            return redirect()->route('student.assignments.show', $assignment->id)->with('success', __('Submission saved.'));
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('student.assignments.show', $assignment->id)->with('error', __('Submission failed.'));
        }
    }

    public function attendanceIndex()
    {
        $sessions = AttendanceSession::orderByDesc('created_at')->paginate(10);

        // fetch existing attendance records for the current user so the UI
        // can indicate which sessions are already marked and disable the button
        $records = AttendanceRecord::where('user_id', auth()->id())->get()->keyBy('attendance_session_id');

        return view('student_portal.attendance.index', compact('sessions', 'records'));
    }

    public function attendanceMark(Request $request, $id)
    {
        $session = AttendanceSession::findOrFail($id);

        // simple availability check
        if ($session->open_at && $session->open_at->gt(now())) {
            return back()->with('error', __('Attendance is not open yet.'));
        }
        if ($session->close_at && $session->close_at->lt(now())) {
            return back()->with('error', __('Attendance is already closed.'));
        }

        try {
            // prevent multiple markings: if a record exists, do not overwrite
            $exists = AttendanceRecord::where('attendance_session_id', $session->id)
                ->where('user_id', auth()->id())
                ->exists();

            if ($exists) {
                return redirect()->route('student.attendance.index')->with('error', __('You have already recorded attendance for this session.'));
            }

            AttendanceRecord::create([
                'attendance_session_id' => $session->id,
                'user_id' => auth()->id(),
                'present_at' => now(),
            ]);

            return redirect()->route('student.attendance.index')->with('success', __('Attendance recorded.'));
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('student.attendance.index')->with('error', __('Attendance failed.'));
        }
    }

    public function editProfile()
    {
        $user = auth()->user();

        return view('student_portal.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }
        $user->save();

        return redirect()->route('student.profile.edit')->with('success', __('Profile updated.'));
    }
}
