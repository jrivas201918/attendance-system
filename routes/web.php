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
