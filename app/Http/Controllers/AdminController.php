<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        // Get overall statistics
        $totalUsers = User::count();
        $totalStudents = Student::count();
        $totalAttendanceRecords = Attendance::count();
        
        // Get attendance statistics for the current month
        $currentMonth = now()->format('Y-m');
        $monthlyAttendance = Attendance::where('created_at', 'like', $currentMonth . '%')->count();
        
        // Get attendance by course
        $attendanceByCourse = DB::table('attendances')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->select('students.course', DB::raw('count(*) as attendance_count'))
            ->groupBy('students.course')
            ->get();
        
        // Get recent activities
        $recentStudents = Student::with('user')->latest()->take(5)->get();
        $recentAttendance = Attendance::with('student')->latest()->take(10)->get();
        
        // Get attendance rate
        $totalPossibleAttendance = $totalStudents * 30; // Assuming 30 days
        $attendanceRate = $totalPossibleAttendance > 0 ? round(($totalAttendanceRecords / $totalPossibleAttendance) * 100, 2) : 0;
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents', 
            'totalAttendanceRecords',
            'monthlyAttendance',
            'attendanceByCourse',
            'recentStudents',
            'recentAttendance',
            'attendanceRate'
        ));
    }

    public function users()
    {
        $users = User::withCount('students')->latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function statistics()
    {
        // Monthly attendance trends
        $monthlyTrends = DB::table('attendances')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        // Course-wise statistics
        $courseStats = DB::table('students')
            ->select('course', DB::raw('count(*) as student_count'))
            ->groupBy('course')
            ->get();

        return view('admin.statistics', compact('monthlyTrends', 'courseStats'));
    }
} 