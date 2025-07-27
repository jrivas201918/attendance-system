<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

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
} 