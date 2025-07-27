<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Get unique courses and years for filter dropdowns
        $courses = Student::query()->select('course')->distinct()->pluck('course')->filter()->sort()->values();
        $years = Student::query()->select('year_level')->distinct()->pluck('year_level')->filter()->sort()->values();

        // Build the query for filtering/search
        $query = Student::query()->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('student_id', 'like', "%$search%");
            });
        }
        if ($request->filled('course')) {
            $query->where('course', $request->input('course'));
        }
        if ($request->filled('year')) {
            $query->where('year_level', $request->input('year'));
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        return view('students.index', compact('students', 'courses', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $courses = [
            'Computer Science',
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Engineering',
            'Business Administration',
            'Psychology',
            'History',
            'English Literature',
        ];
        return view('students.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:students',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students',
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:10',
        ]);

        Student::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'course' => $request->course,
            'year_level' => $request->year_level,
            'user_id' => auth()->id(), // This line is critical!
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully! 🎉');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student, Request $request): View
    {
        if ($student->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        $attendancesQuery = $student->attendances()->orderBy('date', 'desc');

        if ($request->filled('filter_date')) {
            $attendancesQuery->where('date', $request->input('filter_date'));
        } elseif ($request->filled('filter_month')) {
            $month = $request->input('filter_month'); // format: YYYY-MM
            $attendancesQuery->whereYear('date', substr($month, 0, 4))
                ->whereMonth('date', substr($month, 5, 2));
        }
        
        // Clone the query for summary calculations before pagination
        $summaryQuery = clone $attendancesQuery;
        $allAttendances = $summaryQuery->get();

        $total = $allAttendances->count();
        $present = $allAttendances->where('status', 'present')->count();
        $absent = $allAttendances->where('status', 'absent')->count();
        
        $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        
        $attendanceSummary = [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'percentage' => $percentage,
        ];

        // Paginate the results for display
        $attendances = $attendancesQuery->paginate(10);

        return view('students.show', compact('student', 'attendances', 'attendanceSummary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        if ($student->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        $courses = ['Engineering', 'Information Technology', 'Business Administration', 'Education', 'Arts and Sciences'];
        return view('students.edit', compact('student', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        if ($student->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:students,student_id,' . $student->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $student->id,
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:10',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully! ✏️');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        if ($student->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully! 🗑️');
    }
}
