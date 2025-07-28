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
            
            // Get chart data
            $chartData = $this->getChartData();
            
            return view('admin.dashboard', compact(
                'totalUsers',
                'totalStudents', 
                'totalAttendanceRecords',
                'monthlyAttendance',
                'attendanceByCourse',
                'recentStudents',
                'recentAttendance',
                'attendanceRate',
                'chartData'
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
                'attendanceRate' => 0,
                'chartData' => $this->getChartData()
            ]);
        }
    }

    public function edit(User $user)
    {
        // Ensure only teachers can be edited
        if ($user->role !== 'teacher') {
            abort(403, 'Only teachers can be edited.');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Ensure only teachers can be updated
        if ($user->role !== 'teacher') {
            abort(403, 'Only teachers can be updated.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:teacher'
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.users')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $user)
    {
        // Ensure only teachers can be deleted
        if ($user->role !== 'teacher') {
            abort(403, 'Only teachers can be deleted.');
        }

        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            abort(403, 'You cannot delete your own account.');
        }

        // Delete associated students first
        $user->students()->delete();
        
        // Delete the user
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Teacher deleted successfully.');
    }

    public function users()
    {
        try {
            // Only show teachers, not admins, with student count
            $users = User::where('role', 'teacher')
                ->withCount('students')
                ->latest()
                ->paginate(10);
            return view('admin.users', compact('users'));
        } catch (\Exception $e) {
            // Fallback to simple user list
            $users = User::where('role', 'teacher')
                ->withCount('students')
                ->latest()
                ->get();
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
    
    private function getChartData()
    {
        try {
            // Get student distribution by course (for pie chart)
            $courseDistribution = collect();
            try {
                $courseDistribution = DB::table('students')
                    ->select('course', DB::raw('count(*) as student_count'))
                    ->groupBy('course')
                    ->get();
            } catch (\Exception $e) {
                $courseDistribution = collect();
            }
            
            // Get daily attendance data for current month (for bar chart)
            $dailyAttendance = collect();
            try {
                $currentMonth = now()->format('Y-m');
                $dailyAttendance = DB::table('attendances')
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('status'),
                        DB::raw('count(*) as count')
                    )
                    ->where('created_at', 'like', $currentMonth . '%')
                    ->groupBy('date', 'status')
                    ->orderBy('date')
                    ->get();
            } catch (\Exception $e) {
                $dailyAttendance = collect();
            }
            
            // Process daily attendance data
            $processedDailyData = [];
            $dates = [];
            $presentData = [];
            $absentData = [];
            
            if ($dailyAttendance->count() > 0) {
                // Get unique dates
                $uniqueDates = $dailyAttendance->pluck('date')->unique()->sort();
                
                foreach ($uniqueDates as $date) {
                    $dates[] = date('M d', strtotime($date));
                    
                    $presentCount = $dailyAttendance
                        ->where('date', $date)
                        ->where('status', 'present')
                        ->first()->count ?? 0;
                    
                    $absentCount = $dailyAttendance
                        ->where('date', $date)
                        ->where('status', 'absent')
                        ->first()->count ?? 0;
                    
                    $presentData[] = $presentCount;
                    $absentData[] = $absentCount;
                }
            }
            
            return [
                'courseDistribution' => $courseDistribution,
                'dailyAttendance' => [
                    'dates' => $dates,
                    'present' => $presentData,
                    'absent' => $absentData
                ]
            ];
        } catch (\Exception $e) {
            return [
                'courseDistribution' => collect(),
                'dailyAttendance' => [
                    'dates' => [],
                    'present' => [],
                    'absent' => []
                ]
            ];
        }
    }
} 