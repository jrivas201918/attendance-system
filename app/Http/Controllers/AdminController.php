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
        // Remove middleware from constructor since it's applied in routes
        // $this->middleware('auth');
        // $this->middleware('admin');
    }

    public function dashboard()
    {
        try {
            // Get overall statistics
            $totalUsers = User::count();
            $totalStudents = Student::count();
            $totalAttendanceRecords = Attendance::count();
            
            // Get attendance statistics for the current month
            $currentMonth = now()->format('Y-m');
            $monthlyAttendance = Attendance::where('created_at', 'like', $currentMonth . '%')->count();
            
            // Get attendance by course (with error handling)
            $attendanceByCourse = collect();
            try {
                $attendanceByCourse = DB::table('attendances')
                    ->join('students', 'attendances.student_id', '=', 'students.id')
                    ->select('students.course', DB::raw('count(*) as attendance_count'))
                    ->groupBy('students.course')
                    ->get();
            } catch (\Exception $e) {
                // If join fails, return empty collection
                $attendanceByCourse = collect();
            }
            
            // Get recent activities (with error handling)
            $recentStudents = collect();
            $recentAttendance = collect();
            try {
                $recentStudents = Student::latest()->take(5)->get();
                $recentAttendance = Attendance::latest()->take(10)->get();
            } catch (\Exception $e) {
                // If relationships don't exist, return empty collections
                $recentStudents = collect();
                $recentAttendance = collect();
            }
            
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
        } catch (\Exception $e) {
            // Fallback to simple dashboard
            return view('admin.dashboard', [
                'totalUsers' => User::count(),
                'totalStudents' => Student::count(),
                'totalAttendanceRecords' => 0,
                'monthlyAttendance' => 0,
                'attendanceByCourse' => collect(),
                'recentStudents' => collect(),
                'recentAttendance' => collect(),
                'attendanceRate' => 0
            ]);
        }
    }

    public function users()
    {
        try {
            $users = User::latest()->paginate(10);
            return view('admin.users', compact('users'));
        } catch (\Exception $e) {
            // Fallback to simple user list
            $users = User::latest()->get();
            return view('admin.users', compact('users'));
        }
    }

    public function statistics()
    {
        try {
            // Monthly attendance trends
            $monthlyTrends = collect();
            try {
                $monthlyTrends = DB::table('attendances')
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                        DB::raw('count(*) as count')
                    )
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->take(12)
                    ->get();
            } catch (\Exception $e) {
                // If query fails, return empty collection
                $monthlyTrends = collect();
            }

            // Course-wise statistics
            $courseStats = collect();
            try {
                $courseStats = DB::table('students')
                    ->select('course', DB::raw('count(*) as student_count'))
                    ->groupBy('course')
                    ->get();
            } catch (\Exception $e) {
                // If query fails, return empty collection
                $courseStats = collect();
            }

            return view('admin.statistics', compact('monthlyTrends', 'courseStats'));
        } catch (\Exception $e) {
            // Fallback to empty data
            return view('admin.statistics', [
                'monthlyTrends' => collect(),
                'courseStats' => collect()
            ]);
        }
    }
} 