<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class TeacherAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = auth()->user();
            
            // Only show for teachers
            if (!$user->isTeacher()) {
                abort(403, 'Only teachers can view this page.');
            }

            // Pie chart: students by course (for this teacher)
            $courseDistribution = \DB::table('students')
                ->select('course', \DB::raw('count(*) as student_count'))
                ->where('user_id', $user->id)
                ->groupBy('course')
                ->get();

            // Bar chart: daily attendance for this teacher's students (current month)
            $studentIds = \App\Models\Student::where('user_id', $user->id)->pluck('id');
            $dates = [];
            $presentData = [];
            $absentData = [];

            if ($studentIds->count() > 0) {
                $currentMonth = now()->format('Y-m');
                $dailyAttendance = \DB::table('attendances')
                    ->select(
                        \DB::raw('DATE(date) as date'),
                        \DB::raw('status'),
                        \DB::raw('count(*) as count')
                    )
                    ->whereIn('student_id', $studentIds)
                    ->where('date', 'like', $currentMonth . '%')
                    ->groupBy('date', 'status')
                    ->orderBy('date')
                    ->get();

                $uniqueDates = $dailyAttendance->pluck('date')->unique()->sort();
                foreach ($uniqueDates as $date) {
                    $dates[] = date('M d', strtotime($date));
                    $presentCount = optional($dailyAttendance->where('date', $date)->where('status', 'present')->first())->count ?? 0;
                    $absentCount = optional($dailyAttendance->where('date', $date)->where('status', 'absent')->first())->count ?? 0;
                    $presentData[] = $presentCount;
                    $absentData[] = $absentCount;
                }
            }

            $chartData = [
                'courseDistribution' => $courseDistribution,
                'dailyAttendance' => [
                    'dates' => $dates,
                    'present' => $presentData,
                    'absent' => $absentData
                ]
            ];

            return view('teacher.analytics', compact('chartData'));
            
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('TeacherAnalyticsController error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Return a user-friendly error page
            abort(500, 'An error occurred while loading analytics. Please try again later.');
        }
    }

    public function exportAttendance()
    {
        try {
            $user = auth()->user();
            
            // Only allow teachers
            if (!$user->isTeacher()) {
                abort(403, 'Only teachers can export attendance data.');
            }

            // Get all students for this teacher
            $students = Student::where('user_id', $user->id)->get();
            
            if ($students->count() == 0) {
                return back()->with('error', 'No students found to export.');
            }

            $studentIds = $students->pluck('id');
            
            // Get all attendance records for these students
            $attendanceData = DB::table('attendances')
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->select(
                    'students.student_id as student_id',
                    'students.name as student_name',
                    'students.email as student_email',
                    'students.course',
                    'students.year_level',
                    'attendances.date',
                    'attendances.status',
                    'attendances.created_at'
                )
                ->whereIn('attendances.student_id', $studentIds)
                ->orderBy('students.name')
                ->orderBy('attendances.date')
                ->get();

            // Generate CSV content
            $csvContent = "Student ID,Student Name,Email,Course,Year Level,Date,Status,Recorded At\n";
            
            foreach ($attendanceData as $record) {
                $csvContent .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                    $record->student_id,
                    $record->student_name,
                    $record->student_email,
                    $record->course,
                    $record->year_level,
                    $record->date,
                    $record->status,
                    $record->created_at
                );
            }

            // Generate filename with current date
            $filename = 'attendance_export_' . $user->name . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

            // Return CSV download
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error('Export attendance error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export attendance data. Please try again.');
        }
    }

    public function exportStudents()
    {
        try {
            $user = auth()->user();
            
            // Only allow teachers
            if (!$user->isTeacher()) {
                abort(403, 'Only teachers can export student data.');
            }

            // Get all students for this teacher
            $students = Student::where('user_id', $user->id)->get();
            
            if ($students->count() == 0) {
                return back()->with('error', 'No students found to export.');
            }

            // Generate CSV content
            $csvContent = "Student ID,Name,Email,Course,Year Level,Created At\n";
            
            foreach ($students as $student) {
                $csvContent .= sprintf(
                    '"%s","%s","%s","%s","%s","%s"' . "\n",
                    $student->student_id,
                    $student->name,
                    $student->email,
                    $student->course,
                    $student->year_level,
                    $student->created_at
                );
            }

            // Generate filename with current date
            $filename = 'students_export_' . $user->name . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

            // Return CSV download
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error('Export students error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export student data. Please try again.');
        }
    }
} 