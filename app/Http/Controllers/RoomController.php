<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index()
    {
        $rooms = Room::where('user_id', auth()->id())
            ->withCount('students')
            ->latest()
            ->get();

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $room = Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully! 🏠');
    }

    /**
     * Display the specified room with its students.
     */
    public function show(Room $room)
    {
        // Ensure user can only access their own rooms
        if ($room->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        $students = $room->students()->orderBy('name')->get();
        
        return view('rooms.show', compact('room', 'students'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        // Ensure user can only access their own rooms
        if ($room->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, Room $room)
    {
        // Ensure user can only access their own rooms
        if ($room->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $room->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully! ✏️');
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Room $room)
    {
        // Ensure user can only access their own rooms
        if ($room->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully! 🗑️');
    }

    /**
     * Show attendance marking interface for a specific room.
     */
    public function attendance(Room $room)
    {
        // Ensure user can only access their own rooms
        if ($room->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        $students = $room->students()->orderBy('name')->get();
        
        // Get today's attendance for this room
        $todayAttendance = $room->attendances()
            ->whereDate('created_at', today())
            ->with('student')
            ->get()
            ->keyBy('student_id');

        return view('rooms.attendance', compact('room', 'students', 'todayAttendance'));
    }

    /**
     * Save attendance for a room.
     */
    public function saveAttendance(Request $request, Room $room)
    {
        // Ensure user can only access their own rooms
        if ($room->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent',
        ]);

        DB::transaction(function () use ($request, $room) {
            // Delete existing attendance for today
            $room->attendances()
                ->whereDate('created_at', today())
                ->delete();

            // Create new attendance records
            foreach ($request->attendance as $studentId => $status) {
                $student = Student::where('id', $studentId)
                    ->where('user_id', auth()->id())
                    ->first();

                if ($student && $student->room_id === $room->id) {
                    Attendance::create([
                        'student_id' => $studentId,
                        'room_id' => $room->id,
                        'status' => $status,
                        'date' => today(),
                    ]);
                }
            }
        });

        return redirect()->route('rooms.attendance', $room)
            ->with('success', 'Attendance saved successfully! ✅');
    }
} 