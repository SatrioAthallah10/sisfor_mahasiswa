<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = Student::count();
        $averageGpa = round(Student::avg('gpa') ?? 0, 2);
        
        $studentsByProdi = Student::selectRaw('prodi, COUNT(*) as total')
            ->groupBy('prodi')
            ->orderByDesc('total')
            ->get();

        return view('dashboard.index', compact('totalStudents', 'averageGpa', 'studentsByProdi'));
    }
}
