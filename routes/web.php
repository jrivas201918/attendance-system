<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $lastLogin = auth()->user()->last_login_at;

    $apiKey = '2e252882b36a22d5a6590224cbcd6244';
    $city = 'Manila';
    $weather = null;

    try {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);
        if ($response->successful()) {
            $weather = $response->json();
        }
    } catch (\Exception $e) {
        $weather = null;
    }

    return view('dashboard', compact('lastLogin', 'weather'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Student routes
    Route::resource('students', StudentController::class);

    // Attendance routes
    Route::get('/attendance/create', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}/edit', [\App\Http\Controllers\AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/attendance/export/{student}', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/statistics', [\App\Http\Controllers\AdminController::class, 'statistics'])->name('admin.statistics');
    });

    Route::get('/teacher/analytics', [\App\Http\Controllers\TeacherAnalyticsController::class, 'index'])->name('teacher.analytics');
});

require __DIR__.'/auth.php';

Route::get('/debug-admin', function () {
    return [
        'user' => auth()->user() ? [
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'is_admin' => auth()->user()->isAdmin()
        ] : 'Not logged in',
        'admin_middleware_exists' => class_exists(\App\Http\Middleware\AdminMiddleware::class),
        'admin_controller_exists' => class_exists(\App\Http\Controllers\AdminController::class)
    ];
});

Route::get('/test-admin', function () {
    if (!auth()->check()) {
        return ['error' => 'Not logged in'];
    }
    
    if (!auth()->user()->isAdmin()) {
        return ['error' => 'Not admin'];
    }
    
    return ['success' => 'Admin access confirmed!'];
})->middleware('admin');

Route::get('/test-admin-controller', function () {
    try {
        $controller = new \App\Http\Controllers\AdminController();
        return ['success' => 'AdminController exists and can be instantiated'];
    } catch (Exception $e) {
        return ['error' => 'AdminController error: ' . $e->getMessage()];
    }
});

Route::get('/simple-admin-dashboard', function () {
    try {
        // Simple admin dashboard without complex queries
        $totalUsers = \App\Models\User::count();
        $totalStudents = \App\Models\Student::count();
        
        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalAttendanceRecords' => 0,
            'monthlyAttendance' => 0,
            'attendanceByCourse' => collect(),
            'recentStudents' => collect(),
            'recentAttendance' => collect(),
            'attendanceRate' => 0
        ]);
    } catch (Exception $e) {
        return ['error' => 'Simple admin dashboard error: ' . $e->getMessage()];
    }
})->middleware('admin');

Route::get('/simple-admin-test', function () {
    return [
        'auth_check' => auth()->check(),
        'user_exists' => auth()->user() ? 'yes' : 'no',
        'is_admin' => auth()->user() ? auth()->user()->isAdmin() : 'no user',
        'role' => auth()->user() ? auth()->user()->role : 'no user',
        'middleware_exists' => class_exists(\App\Http\Middleware\AdminMiddleware::class),
        'controller_exists' => class_exists(\App\Http\Controllers\AdminController::class),
        'view_exists' => view()->exists('admin.dashboard')
    ];
});

Route::get('/test-database', function () {
    try {
        // Test database connection
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        
        // Check if users table has role column
        $hasRoleColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'role');
        
        // Get user count
        $userCount = \App\Models\User::count();
        
        // Get current user from database
        $currentUser = auth()->user();
        $dbUser = null;
        if ($currentUser) {
            $dbUser = \App\Models\User::find($currentUser->id);
        }
        
        return [
            'database_connected' => true,
            'has_role_column' => $hasRoleColumn,
            'user_count' => $userCount,
            'current_user_role' => $dbUser ? $dbUser->role : 'not found',
            'current_user_is_admin' => $dbUser ? $dbUser->isAdmin() : false
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'database_connected' => false
        ];
    }
});

Route::get('/debug-teacher-analytics', function () {
    try {
        $user = auth()->user();
        if (!$user) {
            return 'Not logged in!';
        }
        if (!$user->isTeacher()) {
            return 'Not a teacher!';
        }

        // Copy the logic from TeacherAnalyticsController@index here:
        $courseDistribution = \DB::table('students')
            ->select('course', \DB::raw('count(*) as student_count'))
            ->where('user_id', $user->id)
            ->groupBy('course')
            ->get();

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

        return [
            'courseDistribution' => $courseDistribution,
            'dailyAttendance' => [
                'dates' => $dates,
                'present' => $presentData,
                'absent' => $absentData
            ]
        ];
    } catch (\Throwable $e) {
        return response('<pre>' . e($e) . '</pre>', 500);
    }
});

Route::get('/test-teacher-analytics', function () {
    try {
        // Test if controller can be instantiated
        $controller = new \App\Http\Controllers\TeacherAnalyticsController();
        
        // Test if view exists
        $viewExists = view()->exists('teacher.analytics');
        
        // Test if user is logged in and is teacher
        $user = auth()->user();
        $isTeacher = $user ? $user->isTeacher() : false;
        
        return [
            'controller_exists' => true,
            'view_exists' => $viewExists,
            'user_logged_in' => $user ? true : false,
            'is_teacher' => $isTeacher,
            'user_role' => $user ? $user->role : 'not logged in'
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});

Route::get('/test-teacher-view', function () {
    try {
        $chartData = [
            'courseDistribution' => [
                ['course' => 'Engineering', 'student_count' => 1]
            ],
            'dailyAttendance' => [
                'dates' => [],
                'present' => [],
                'absent' => []
            ]
        ];
        
        return view('teacher.analytics', compact('chartData'));
    } catch (Exception $e) {
        return response('<pre>' . e($e) . '</pre>', 500);
    }
});
