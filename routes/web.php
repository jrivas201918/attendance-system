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
});

require __DIR__.'/auth.php';

Route::get('/check-user/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        return [
            'exists' => true,
            'user_id' => $user->id,
            'email' => $user->email,
            'created_at' => $user->created_at
        ];
    } else {
        return [
            'exists' => false,
            'message' => 'User not found'
        ];
    }
});

Route::get('/test-reset/{email}', function ($email) {
    try {
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            return ['error' => 'User not found'];
        }
        
        \Illuminate\Support\Facades\Password::sendResetLink(['email' => $email]);
        return ['success' => 'Password reset link sent to ' . $email];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});
