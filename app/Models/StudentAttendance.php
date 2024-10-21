<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_session_id',
        'biometric_attendance',
        'attendance_type_id',
        'date',
        'remarks',
        'is_active',
    ];

    public function studentSession()
    {
        return $this->belongsTo(StudentSession::class, 'student_session_id', 'id');
    }

    public function attendenceType()
    {
        return $this->belongsTo(AttendanceType::class, 'attendance_type_id');
    }


     // Define the relationship with the Student model
     public function student() {
        return $this->belongsTo(Student::class, 'student_session_id', 'id');
    }
}