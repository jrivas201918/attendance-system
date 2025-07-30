<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    /**
     * Get the user that owns the room.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the students assigned to this room.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the attendances for this room.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the count of students in this room.
     */
    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

    /**
     * Get today's attendance for this room.
     */
    public function getTodayAttendanceAttribute()
    {
        return $this->attendances()
            ->whereDate('created_at', today())
            ->get();
    }
} 