<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating attendance records for all students.
     */
    public function create(Request $request)
    {
        // Get all students for the current teacher
        $students = Student::where('user_id', auth()->id())->get();
        
        // Get today's date
        $today = date('Y-m-d');
        
        // Get existing attendance records for today
        $existingAttendance = Attendance::whereIn('student_id', $students->pluck('id'))
            ->where('date', $today)
            ->pluck('status', 'student_id')
            ->toArray();
        
        return view('attendance.create', compact('students', 'today', 'existingAttendance'));
    }

    /**
     * Store attendance records for multiple students.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent',
        ]);

        $date = $validated['date'];
        $attendanceData = $validated['attendance'];
        
        // Get all students for the current teacher
        $students = Student::where('user_id', auth()->id())->pluck('id')->toArray();
        
        // Delete existing attendance records for today
        Attendance::whereIn('student_id', $students)
            ->where('date', $date)
            ->delete();
        
        // Create new attendance records
        $records = [];
        foreach ($attendanceData as $studentId => $status) {
            if (in_array($studentId, $students)) {
                $records[] = [
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        if (!empty($records)) {
            Attendance::insert($records);
        }

        return redirect()->route('attendance.create')
            ->with('success', 'Attendance marked successfully for all students.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $student = $attendance->student;
        return view('attendance.edit', compact('attendance', 'student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        // Prevent duplicate attendance for the same student and date (except for this record)
        $exists = Attendance::where('student_id', $attendance->student_id)
            ->where('date', $validated['date'])
            ->where('id', '!=', $attendance->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['date' => 'Attendance for this date already exists.'])->withInput();
        }

        $attendance->update($validated);

        return redirect()->route('students.show', $attendance->student_id)
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $studentId = $attendance->student_id;
        $attendance->delete();
        return redirect()->route('students.show', $studentId)
            ->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * Export a student's attendance records as CSV.
     */
    public function export(Student $student)
    {
        $attendances = $student->attendances()->orderBy('date')->get();
        $filename = 'attendance_' . $student->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function() use ($attendances, $student) {
            $handle = fopen('php://output', 'w');
            // CSV header
            fputcsv($handle, ['Student Name', 'Date', 'Status']);
            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $student->name,
                    $attendance->date,
                    ucfirst($attendance->status),
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }
}
