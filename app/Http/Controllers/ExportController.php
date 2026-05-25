<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export all students data to a CSV file.
     */
    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_' . date('Ymd') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            
            // CSV Header Row
            fputcsv($handle, ['NIM', 'Name', 'Study Program', 'GPA', 'Photo', 'Created At']);

            // Chunk records in batches of 200 to conserve memory
            Student::select('nim', 'name', 'prodi', 'gpa', 'photo_path', 'created_at')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->nim,
                            $row->name,
                            $row->prodi,
                            $row->gpa,
                            $row->photo_path ?? '',
                            $row->created_at
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
