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

Route::get('/debug', function () {
    return [
        'mail_mailer' => env('MAIL_MAILER'),
        'mail_host' => env('MAIL_HOST'),
        'mail_port' => env('MAIL_PORT'),
        'mail_username' => env('MAIL_USERNAME'),
        'mail_from_address' => env('MAIL_FROM_ADDRESS'),
        'app_debug' => env('APP_DEBUG'),
    ];
});
