<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LecturerPortalController extends Controller
{

    public function dashboard()
    {
        $courseIds = $this->myCourseIds();

        $pendingSubmissions = Submission::whereIn(
            'assignment_id',
            Assignment::whereIn('course_id', $courseIds)->pluck('id')
        )->where('status', 'pending')->count();

        $pendingAttendance = AttendanceRecord::whereIn(
            'attendance_session_id',
            AttendanceSession::whereIn('course_id', $courseIds)->pluck('id')
        )->where('status', 'pending')->count();

        $totalCourses = count($courseIds);

        return view('lecturer_portal.dashboard', compact(
            'pendingSubmissions',
            'pendingAttendance',
            'totalCourses'
        ));
    }

    public function coursesIndex()
    {
        $courses = Course::where('lecturer_id', auth()->id())
            ->withCount(['assignments', 'attendanceSessions' => function ($q) {
            }])
            ->get();

        return view('lecturer_portal.courses.index', compact('courses'));
    }

    public function assignmentsIndex()
    {
        $courseIds = $this->myCourseIds();

        $assignments = Assignment::whereIn('course_id', $courseIds)
            ->with('course')
            ->withCount([
                'submissions',
                'submissions as pending_count'   => fn($q) => $q->where('status', 'pending'),
                'submissions as diterima_count'  => fn($q) => $q->where('status', 'diterima'),
                'submissions as ditolak_count'   => fn($q) => $q->where('status', 'ditolak'),
            ])
            ->latest()
            ->paginate(10);

        return view('lecturer_portal.assignments.index', compact('assignments'));
    }

    public function assignmentsCreate()
    {
        $courses = Course::where('lecturer_id', auth()->id())->get();
        return view('lecturer_portal.assignments.create', compact('courses'));
    }

    public function assignmentsStore(Request $request)
    {
        $validated = $request->validate([
            'course_id'      => ['required', 'exists:courses,id'],
            'title'          => ['required', 'string', 'max:255'],
            'question'       => ['nullable', 'string'],
            'description'    => ['nullable', 'string'],
            'question_file'  => ['nullable', 'file', 'max:20480'],
            'available_from' => ['nullable', 'date'],
            'due_at'         => ['nullable', 'date'],
        ]);

        $this->authorizeCourseOwnership($validated['course_id']);

        $filePath = null;
        if ($request->hasFile('question_file')) {
            $filePath = $request->file('question_file')->store('assignments', 'public');
        }

        Assignment::create([
            ...$validated,
            'question_file' => $filePath,
            'created_by'    => auth()->id(),
        ]);

        return redirect()->route('lecturer.assignments.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function submissionsIndex($assignmentId)
    {
        $assignment = Assignment::with('course')->findOrFail($assignmentId);
        $this->authorizeCourseOwnership($assignment->course_id);

        $submissions = Submission::with('user')
            ->where('assignment_id', $assignmentId)
            ->latest('submitted_at')
            ->paginate(20);

        return view('lecturer_portal.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function submissionApprove($id)
    {
        $submission = Submission::with('assignment')->findOrFail($id);
        $this->authorizeCourseOwnership($submission->assignment->course_id);

        $submission->update([
            'status'      => 'diterima',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Submission diterima.');
    }

    public function submissionReject(Request $request, $id)
    {
        $request->validate([
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission = Submission::with('assignment')->findOrFail($id);
        $this->authorizeCourseOwnership($submission->assignment->course_id);

        $submission->update([
            'status'      => 'ditolak',
            'feedback'    => $request->input('feedback'),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Submission ditolak.');
    }

    public function attendanceIndex()
    {
        $courseIds = $this->myCourseIds();

        $sessions = AttendanceSession::whereIn('course_id', $courseIds)
            ->with('course')
            ->withCount([
                'records',
                'records as pending_count'  => fn($q) => $q->where('status', 'pending'),
                'records as diterima_count' => fn($q) => $q->where('status', 'diterima'),
                'records as ditolak_count'  => fn($q) => $q->where('status', 'ditolak'),
            ])
            ->latest()
            ->paginate(10);

        return view('lecturer_portal.attendance.index', compact('sessions'));
    }

    public function attendanceRecords($sessionId)
    {
        $session = AttendanceSession::with('course')->findOrFail($sessionId);
        $this->authorizeCourseOwnership($session->course_id);

        $records = AttendanceRecord::with('user')
            ->where('attendance_session_id', $sessionId)
            ->latest('present_at')
            ->paginate(25);

        return view('lecturer_portal.attendance.records', compact('session', 'records'));
    }

    public function attendanceApprove($id)
    {
        $record = AttendanceRecord::with('session')->findOrFail($id);
        $this->authorizeCourseOwnership($record->session->course_id);

        $record->update(['status' => 'diterima']);

        return back()->with('success', 'Absensi diterima.');
    }

    public function attendanceReject($id)
    {
        $record = AttendanceRecord::with('session')->findOrFail($id);
        $this->authorizeCourseOwnership($record->session->course_id);

        $record->update(['status' => 'ditolak']);

        return back()->with('success', 'Absensi ditolak.');
    }

    private function myCourseIds(): array
    {
        return Course::where('lecturer_id', auth()->id())->pluck('id')->toArray();
    }

    private function authorizeCourseOwnership($courseId): void
    {
        $owns = Course::where('id', $courseId)
            ->where('lecturer_id', auth()->id())
            ->exists();

        if (! $owns) {
            abort(403, 'Akses ditolak.');
        }
    }
}
