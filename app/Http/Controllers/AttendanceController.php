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
     * Show the form for creating a new attendance record for a student.
     */
    public function create(Request $request)
    {
        $studentId = $request->query('student_id');
        $student = Student::findOrFail($studentId);
        return view('attendance.create', compact('student'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        // Prevent duplicate attendance for the same student and date
        $exists = Attendance::where('student_id', $validated['student_id'])
            ->where('date', $validated['date'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['date' => 'Attendance for this date already exists.'])->withInput();
        }

        Attendance::create($validated);

        return redirect()->route('students.show', $validated['student_id'])
            ->with('success', 'Attendance marked successfully.');
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
