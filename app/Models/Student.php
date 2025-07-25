<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Add this
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory; // <-- Add this

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'course',
        'year_level',
        'user_id', // <-- Add this line!
    ];

    protected $casts = [
        'year_level' => 'integer',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}